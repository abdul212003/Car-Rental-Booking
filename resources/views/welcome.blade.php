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
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('images/rj-pic.jpeg');
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

        .navbar {
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .section-padding {
            padding: 80px 0;
        }

        .feedback-card {
            border-left: 4px solid #0d6efd;
        }

        .contact-info i {
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            background-color: #0d6efd;
            color: white;
            margin-right: 15px;
        }

        .contact-form .form-control {
            border-radius: 0;
            border: 1px solid #ddd;
            padding: 12px 15px;
        }

        .contact-form .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }

        .btn-primary {
            border-radius: 0;
            padding: 12px 30px;
        }

        .about-img {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #0d6efd;
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#available-cars">Cars</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#feedback">Feedback</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold">Drive Your Dream Car Today</h1>
            <p class="lead">Affordable vehicles at resonable prices. No account needed to book.</p>
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

    <!-- About Us Section -->
    <section id="about" class="section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="mb-4">About RJ Car Rentals</h2>
                    <p class="lead">We've been providing premium car rental services since 2010, with a commitment to
                        excellence and customer satisfaction.</p>
                    <p>Our mission is to make luxury car rentals accessible to everyone. With a diverse fleet of
                        well-maintained vehicles and a team of dedicated professionals, we ensure that every rental
                        experience is seamless and memorable.</p>
                    <p>Whether you need a car for business, leisure, or a special occasion, RJ Car Rentals has the
                        perfect vehicle for your needs.</p>

                    <div class="row mt-4">
                        <div class="col-4 text-center">
                            <div class="stats-number">500+</div>
                            <p>Happy Customers</p>
                        </div>
                        <div class="col-4 text-center">
                            <div class="stats-number">50+</div>
                            <p>Premium Vehicles</p>
                        </div>
                        <div class="col-4 text-center">
                            <div class="stats-number">15</div>
                            <p>Years Experience</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="images/rj-pic.jpeg" alt="About RJ Car Rentals"
                        class="img-fluid about-img mx-auto d-block h-50 w-auto">
                </div>
            </div>
        </div>
    </section>

    <!-- Feedback Section -->
    <section id="feedback" class="section-padding bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Customer Feedback</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card feedback-card h-100">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">Sarah Johnson</h5>
                                    <div class="text-success fw-normal">
                                        <p>Excellent</p>
                                    </div>
                                </div>
                            </div>
                            <p class="card-text">"The booking process was incredibly smooth, and the car was in perfect
                                condition. Will definitely use RJ Car Rentals again!"</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feedback-card h-100">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">Michael Chen</h5>
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="card-text">"Excellent service! The team was professional and the vehicle exceeded
                                my expectations. Highly recommended for business trips."</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feedback-card h-100">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0">Emily Rodriguez</h5>
                                    <div class="text-warning">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="card-text">"Rented a luxury SUV for our family vacation. The process was
                                hassle-free and the car was perfect for our road trip."</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback Form -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Share Your Experience</h4>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Your Name</label>
                                        <input type="text" class="form-control" id="name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating</label>
                                    <select class="form-select" id="rating" required>
                                        <option value="" selected disabled>Select your rating</option>
                                        <option value="Excellent">Excellent</option>
                                        <option value="Very Good">Very Good</option>
                                        <option value="Good">Good</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Your Feedback</label>
                                    <textarea class="form-control" id="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Feedback</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section id="contact" class="section-padding">
        <div class="container">
            <h2 class="text-center mb-5">Contact Us</h2>
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="mb-4">Get In Touch</h4>
                    <div class="contact-info mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h5 class="mb-0">Address</h5>
                                <p class="mb-0">Villarica Midsayap Cotabato</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-phone"></i>
                            <div>
                                <h5 class="mb-0">Phone</h5>
                                <p class="mb-0">09660244560</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <h5 class="mb-0">Email</h5>
                                <p class="mb-0">royrojas012294@gmaiil.com</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h5 class="mb-0">Business Hours</h5>
                                <p class="mb-0">Monday - Sunday: 24/7</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5 class="mb-3">Follow Us</h5>
                        <div class="d-flex">
                            <a href="https://www.facebook.com/share/1GJgwe3d1f/?mibextid=wwXIfr"
                                class="btn btn-outline-primary me-2" target="_blank"><i
                                    class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="contact-form">
                        <form>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact-name" class="form-label">Your Name</label>
                                    <input type="text" class="form-control" id="contact-name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact-email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="contact-email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact-message" class="form-label">Message</label>
                                <textarea class="form-control" id="contact-message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2025 RJ Car Rentals. All rights reserved.</p>
            <p>Contact: info@rjcarrentals.com | (02) 1234-5678</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>

</html>
