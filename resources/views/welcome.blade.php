<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Car Rentals - Book Your Dream Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @livewireStyles
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        .car-card {
            transition: transform 0.3s ease;
        }
        .car-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-car"></i> RJ Car Rental
            </a>
            <div class="navbar-nav ms-auto">
                <a href="{{ route('login') }}" class="nav-link">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold">Drive Your Dream Car Today</h1>
            <p class="lead">Luxury vehicles at competitive prices. No account needed to book.</p>
            <a href="#available-cars" class="btn btn-primary btn-lg mt-3">Browse Available Cars</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4>Easy Booking</h4>
                    <p>Book online in minutes without creating an account</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h4>Best Prices</h4>
                    <p>Competitive daily rates with no hidden fees</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>24/7 Support</h4>
                    <p>Our team is always ready to assist you</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Available Cars Section -->
    <section id="available-cars" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Available Cars</h2>
            @livewire('car-search') 
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2025 RJ Car Rentals. All rights reserved.</p>
            <p>Contact: info@example.com | (02) 1234-5678</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>
</html>