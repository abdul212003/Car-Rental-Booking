<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('RJ-LOGO-PIC.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
</head>

<body class="bg-light">
    <!-- Navigation Bar -->
    @if (Auth::user()->role == 'admin')
        <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="/dashboard">
                    <i class="bi bi-car-front-fill me-2"></i>RJ Car Rental
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- Mobile Navigation - Hidden on larger screens -->
                    <ul class="navbar-nav d-lg-none">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('addcars') ? 'active' : '' }}" href="/addcars">
                                <i class="fas fa-car me-2"></i> Manage Cars
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('manageBooking') ? 'active' : '' }}"
                                href="/manageBooking">
                                <i class="fas fa-calendar-check me-2"></i> Manage Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('managefeedback') ? 'active' : '' }}"
                                href="/managefeedback">
                                <i class="fa-solid fa-comment me-2"></i> Customer Feedback
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('managecontact') ? 'active' : '' }}"
                                href="/managecontact">
                                <i class="fas fa-address-book me-2"></i> Customer Contacts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('managepayment') ? 'active' : '' }}"
                                href="/managepayment">
                                <i class="fas fa-address-book me-2"></i> Manage Payment
                            </a>
                        </li>
                    </ul>

                    <!-- User Menu -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('user.profile') }}"><i
                                    class="bi bi-person me-2"></i>Profile</a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i
                                        class="bi bi-box-arrow-right me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                    </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar - Hidden on mobile -->
                <div class="col-md-3 col-lg-2 d-none d-md-block bg-white sidebar">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('addcars') ? 'active' : '' }}" href="/addcars">
                                    <i class="fas fa-car me-2"></i> Manage Cars
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('manageBooking') ? 'active' : '' }}"
                                    href="/manageBooking">
                                    <i class="fas fa-calendar-check me-2"></i> Manage Bookings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('managefeedback') ? 'active' : '' }}"
                                    href="/managefeedback">
                                    <i class="fa-solid fa-comment me-2"></i> Customer Feedback
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('managecontact') ? 'active' : '' }}"
                                    href="/managecontact">
                                    <i class="fas fa-address-book me-2"></i> Customer Contacts
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('managepayment') ? 'active' : '' }}"
                                    href="/managepayment">
                                    <i class="fa-solid fa-cash-register me-2"></i> Manage Payment
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
    @endif


    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
        {{ $slot }}
    </main>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    @livewireScripts
</body>

</html>
