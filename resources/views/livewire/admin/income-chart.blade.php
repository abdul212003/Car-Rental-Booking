<div>
    <style>
        .income-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .income-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .income-header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 2.2rem;
            font-weight: 700;
        }

        .income-subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .income-dashboard {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
        }

        .income-chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .income-chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .income-chart-title {
            font-size: 1.5rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .income-chart-controls {
            display: flex;
            gap: 10px;
        }

        .income-btn {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .income-btn-primary {
            background: #3498db;
            color: white;
        }

        .income-btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        .income-btn-outline {
            background: transparent;
            border: 2px solid #3498db;
            color: #3498db;
        }

        .income-btn-outline:hover {
            background: #3498db;
            color: white;
            transform: translateY(-2px);
        }

        .income-stats-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .income-stat-card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .income-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .income-stat-title {
            font-size: 0.95rem;
            color: #7f8c8d;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .income-stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .income-positive {
            color: #27ae60;
        }

        .income-negative {
            color: #e74c3c;
        }

        .income-breakdown {
            margin-top: 10px;
        }

        .income-breakdown-title {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: #2c3e50;
            font-weight: 600;
        }

        @media (max-width: 1024px) {
            .income-dashboard {
                grid-template-columns: 1fr;
            }

            .income-stats-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .income-chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .income-chart-controls {
                width: 100%;
            }

            .income-btn {
                flex: 1;
            }
        }
    </style>

    <div class="income-container">
        <div class="income-dashboard">
            <div class="income-chart-container">
                <div class="income-chart-header">
                    <h2 class="income-chart-title">Income Overview</h2>
                    <div class="income-chart-controls">
                        <button
                            class="income-btn {{ $viewType === 'yearly' ? 'income-btn-primary' : 'income-btn-outline' }}"
                            wire:click="switchView('yearly')">
                            Yearly View
                        </button>
                        <button
                            class="income-btn {{ $viewType === 'monthly' ? 'income-btn-primary' : 'income-btn-outline' }}"
                            wire:click="switchView('monthly')">
                            Monthly View
                        </button>
                    </div>
                </div>
                <canvas id="incomeChart" style="max-height: 400px;"></canvas>
            </div>

            <div class="income-stats-container">
                <div class="income-stat-card">
                    <div class="income-stat-title">💰 Total Income This Year</div>
                    <div class="income-stat-value">₱{{ number_format($totalIncome, 2) }}</div>
                </div>

                <div class="income-stat-card">
                    <div class="income-stat-title">📈 Average Monthly Income</div>
                    <div class="income-stat-value">₱{{ number_format($averageMonthly, 2) }}</div>
                </div>

                {{-- <div class="income-stat-card">
                    <div class="income-stat-title">📊 Growth vs Last Year</div>
                    <div class="income-stat-value {{ $growthPercentage >= 0 ? 'income-positive' : 'income-negative' }}">
                        {{ $growthPercentage >= 0 ? '+' : '' }}{{ number_format($growthPercentage, 1) }}%
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script>
        document.addEventListener('livewire:init', function() {
            let incomeChart = null;

            function initChart() {
                const ctx = document.getElementById('incomeChart');
                if (!ctx) return;

                const viewType = @json($viewType);
                const monthlyData = @json($monthlyData);
                const yearlyData = @json($yearlyData);

                let chartData;
                if (viewType === 'monthly') {
                    chartData = {
                        labels: monthlyData.map(item => item.month),
                        datasets: [{
                            label: 'Monthly Income (₱)',
                            data: monthlyData.map(item => item.amount),
                            backgroundColor: 'rgba(52, 152, 219, 0.6)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            borderWidth: 2,
                            borderRadius: 8,
                            fill: true
                        }]
                    };
                } else {
                    chartData = {
                        labels: yearlyData.map(item => item.year),
                        datasets: [{
                            label: 'Yearly Income (₱)',
                            data: yearlyData.map(item => item.amount),
                            backgroundColor: 'rgba(46, 204, 113, 0.6)',
                            borderColor: 'rgba(46, 204, 113, 1)',
                            borderWidth: 2,
                            borderRadius: 8,
                            fill: true
                        }]
                    };
                }

                if (incomeChart) {
                    incomeChart.destroy();
                }

                incomeChart = new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 13,
                                        weight: 'bold'
                                    }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 13
                                },
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ₱' + context.parsed.y
                                            .toLocaleString('en-PH', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString('en-PH');
                                    },
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
            }

            // Initialize chart on page load
            initChart();

            // Re-initialize chart when Livewire updates
            Livewire.on('livewire:update', () => {
                setTimeout(initChart, 100);
            });
        });
    </script>
</div>
