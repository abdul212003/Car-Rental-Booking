<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\BookingModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IncomeChart extends Component
{
    public $viewType = 'monthly';
    public $currentYear;
    public $monthlyData = [];
    public $yearlyData = [];
    public $totalIncome = 0;
    public $averageMonthly = 0;
    public $growthPercentage = 0;
    public $totalBookings = 0;
    public $incomeBreakdown = [];

    public function mount()
    {
        $this->currentYear = Carbon::now()->year;
        $this->loadData();
    }

    public function loadData()
    {
        $this->loadMonthlyData();
        $this->loadYearlyData();
        $this->calculateStats();
        // $this->loadIncomeBreakdown();
    }

    protected function loadMonthlyData()
    {
        // Get monthly income for current year from confirmed bookings
        $monthlyIncome = BookingModel::where('status', 'confirmed')
            ->whereYear('created_at', $this->currentYear)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_cost) as total'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Initialize all 12 months with zero
        $this->monthlyData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        for ($i = 1; $i <= 12; $i++) {
            $this->monthlyData[] = [
                'month' => $months[$i - 1],
                'amount' => $monthlyIncome->has($i) ? (float)$monthlyIncome[$i]->total : 0,
                'count' => $monthlyIncome->has($i) ? $monthlyIncome[$i]->booking_count : 0
            ];
        }
    }

    protected function loadYearlyData()
    {
        // Get yearly income for the last 5 years
        $startYear = $this->currentYear - 4;
        
        $yearlyIncome = BookingModel::where('status', 'confirmed')
            ->whereYear('created_at', '>=', $startYear)
            ->whereYear('created_at', '<=', $this->currentYear)
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_cost) as total'),
                DB::raw('COUNT(*) as booking_count')
            )
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->keyBy('year');

        $this->yearlyData = [];
        for ($year = $startYear; $year <= $this->currentYear; $year++) {
            $this->yearlyData[] = [
                'year' => $year,
                'amount' => $yearlyIncome->has($year) ? (float)$yearlyIncome[$year]->total : 0,
                'count' => $yearlyIncome->has($year) ? $yearlyIncome[$year]->booking_count : 0
            ];
        }
    }

    protected function calculateStats()
    {
        // Total income this year from confirmed bookings
        $this->totalIncome = collect($this->monthlyData)->sum('amount');

        // Total confirmed bookings this year
        $this->totalBookings = collect($this->monthlyData)->sum('count');

        // Average monthly income (only count months with income)
        $monthsWithIncome = collect($this->monthlyData)->where('amount', '>', 0)->count();
        $this->averageMonthly = $monthsWithIncome > 0 ? $this->totalIncome / $monthsWithIncome : 0;

        // Growth percentage vs last year
        $lastYearTotal = BookingModel::where('status', 'confirmed')
            ->whereYear('created_at', $this->currentYear - 1)
            ->sum('total_cost');

        if ($lastYearTotal > 0) {
            $this->growthPercentage = (($this->totalIncome - $lastYearTotal) / $lastYearTotal) * 100;
        } else {
            $this->growthPercentage = $this->totalIncome > 0 ? 100 : 0;
        }
    }

    // protected function loadIncomeBreakdown()
    // {
    //     // Get income breakdown by car brand or type
    //     $breakdown = BookingModel::where('status', 'confirmed')
    //         ->whereYear('created_at', $this->currentYear)
    //         ->join('cars', 'bookings.car_id', '=', 'cars.id')
    //         ->select(
    //             'cars.brand',
    //             DB::raw('SUM(bookings.total_cost) as total'),
    //             DB::raw('COUNT(*) as booking_count')
    //         )
    //         ->groupBy('cars.brand')
    //         ->orderByDesc('total')
    //         ->limit(5)
    //         ->get();

    //     $this->incomeBreakdown = $breakdown->map(function($item) {
    //         return [
    //             'label' => $item->brand,
    //             'amount' => (float)$item->total,
    //             'count' => $item->booking_count
    //         ];
    //     })->toArray();
    // }

    public function switchView($type)
    {
        $this->viewType = $type;
        $this->dispatch('chartUpdated');
    }

    public function changeYear($year)
    {
        $this->currentYear = $year;
        $this->loadData();
        $this->dispatch('chartUpdated');
    }

    public function refreshData()
    {
        $this->loadData();
        $this->dispatch('chartUpdated');
        session()->flash('message', 'Income data refreshed successfully!');
    }

    public function render()
    {
        if (Auth::check() && Auth::user()->role == 'admin') {
            // Get available years for year selector
            $availableYears = BookingModel::where('status', 'confirmed')
                ->selectRaw('DISTINCT YEAR(created_at) as year')
                ->orderByDesc('year')
                ->pluck('year')
                ->toArray();

            if (empty($availableYears)) {
                $availableYears = [$this->currentYear];
            }

            return view('livewire.admin.income-chart', [
                'availableYears' => $availableYears
            ])->layout('layouts.admin');
        } else {
            abort(403, 'Unauthorized access');
        }
    }
}