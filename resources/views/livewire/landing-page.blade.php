<div>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
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
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#feedback">Feedback</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/payment">Payment</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 fade-in-up">
                    <h1 class="display-4 fw-bold">Drive Your Dream Car Today</h1>
                    <p class="lead">Affordable vehicles at reasonable prices. No account needed to book.</p>
                    <a href="/searchcar" class="btn btn-primary btn-lg mt-3">Browse Available Cars</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-5">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4>Easy Booking</h4>
                    <p class="text-muted">Book online in minutes without creating an account. Simple, fast, and
                        hassle-free.</p>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="feature-icon">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h4>Best Prices</h4>
                    <p class="text-muted">Competitive daily rates with no hidden fees. Premium quality at affordable
                        prices.</p>
                </div>
                <div class="col-md-4 mb-5">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>24/7 Support</h4>
                    <p class="text-muted">Our dedicated team is always ready to assist you, day or night.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about" class="section-padding bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="mb-4">About RJ Car Rentals</h2>
                    <p class="lead mb-4">We've been providing premium car rental services since 2021, with a commitment
                        to excellence and customer satisfaction.</p>
                    <p class="mb-4">Our mission is to make car rentals accessible to everyone. With a diverse
                        fleet of well-maintained vehicles and a team of dedicated professionals, we ensure that every
                        rental experience is seamless and memorable.</p>
                    <p class="mb-5">Whether you need a car for business, leisure, or a special occasion, RJ Car
                        Rentals has the perfect vehicle for your needs.</p>

                    <div class="row mt-4">
                        <div class="col-4 text-center">
                            <div class="stats-number">500+</div>
                            <p class="text-muted">Happy Customers</p>
                        </div>
                        <div class="col-4 text-center">
                            <div class="stats-number">15+</div>
                            <p class="text-muted">Premium Vehicles</p>
                        </div>
                        <div class="col-4 text-center">
                            <div class="stats-number">4</div>
                            <p class="text-muted">Years Experience</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/rj-pic.jpeg') }}" alt="About RJ Car Rentals" class="img-fluid about-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Feedback Section - Simple Star Display -->
    <section id="feedback" class="section-padding">
        <div class="container">
            <h2 class="text-center mb-5">Customer Feedback</h2>
            <div class="row">
                @forelse($Feedbacks as $Feedback)
                    <div class="col-md-4 mb-4">
                        <div class="card feedback-card h-100">
                            <div class="card-body">
                                <div class="d-flex mb-3">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">{{ $Feedback->name }}</h5>
                                        <div class="star-rating mb-1">
                                            @if ($Feedback->rating === 'Excellent')
                                                @for ($i = 0; $i < 5; $i++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                            @elseif($Feedback->rating === 'Very Good')
                                                @for ($i = 0; $i < 4; $i++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                                <i class="fas fa-star text-warning opacity-50"></i>
                                            @elseif($Feedback->rating === 'Good')
                                                @for ($i = 0; $i < 3; $i++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                            @elseif ($Feedback->rating === 'Bad')
                                                @for ($i = 0; $i < 2; $i++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                            @elseif ($Feedback->rating === 'Very Bad')
                                                @for ($i = 0; $i < 1; $i++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                            @endif
                                        </div>
                                        <span
                                            class="badge 
                                        @if ($Feedback->rating === 'Excellent') bg-success
                                        @elseif($Feedback->rating === 'Very Good') bg-warning text-dark
                                        @elseif($Feedback->rating === 'Good') bg-primary
                                        @elseif($Feedback->rating === 'Bad') bg-secondary
                                        @elseif($Feedback->rating === 'Very Bad') bg-danger @endif">
                                            {{ $Feedback->rating }}
                                        </span>
                                    </div>
                                </div>
                                <p class="card-text">"{{ $Feedback->message }}"</p>
                                <small class="text-muted">{{ $Feedback->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">No feedback available yet. Be the first to share your experience!</p>
                    </div>
                @endforelse
            </div>

            <!-- Feedback Form (same as before) -->
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card feedback-card">
                        <div class="card-header text-white">
                            <h4 class="mb-0">Share Your Experience</h4>
                        </div>
                        <div class="card-body">
                            @if (session()->has('feedback_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('feedback_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form wire:submit.prevent="submitFeedback">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Your Name</label>
                                        <input type="text" class="form-control" id="name" wire:model="name"
                                            required>
                                        @error('name')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" wire:model="email"
                                            required>
                                        @error('email')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating</label>
                                    <select class="form-select" id="rating" required wire:model="rating">
                                        <option value="">Select your rating</option>
                                        <option value="Excellent">Excellent (5 stars)</option>
                                        <option value="Very Good">Very Good (4 stars)</option>
                                        <option value="Good">Good (3 stars)</option>
                                        <option value="Bad">Bad (2 stars)</option>
                                        <option value="Very Bad">Very Bad(1 star)</option>
                                    </select>
                                    @error('rating')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Your Feedback</label>
                                    <textarea class="form-control" id="message" rows="4" required wire:model="message"></textarea>
                                    @error('message')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Submit Feedback
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Us Section -->
        <section id="contact" class="section-padding bg-light">
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
                                    <p class="mb-0">royrojas012294@gmail.com</p>
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
                                    class="btn btn-outline-primary me-2" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="contact-form">
                            @if (session()->has('contact_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('contact_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form wire:submit.prevent="submitContact">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="contact-name" class="form-label">Your Name</label>
                                        <input type="text" class="form-control" id="contact-name"
                                            wire:model="contact_name" required>
                                        @error('contact_name')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="contact-email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="contact-email"
                                            wire:model="contact_email" required>
                                        @error('contact_email')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject"
                                        wire:model="contact_subject" required>
                                    @error('contact_subject')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="contact-message" class="form-label">Message</label>
                                    <textarea class="form-control" id="contact-message" rows="5" wire:model="contact_message" required></textarea>
                                    @error('contact_message')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Send Message
                                </button>
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
            </div>
        </footer>

</div>
<script>
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        link.addEventListener('click', function() {
            document.querySelector('.navbar-nav .active')?.classList.remove('active');
            this.classList.add('active');
        });
    });
</script>
