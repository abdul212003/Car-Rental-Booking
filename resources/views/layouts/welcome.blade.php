<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Premium Car Rentals - Book Your Dream Car</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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
            color: #6f42c1;
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
            border-left: 4px solid #6f42c1;
        }

        .contact-info i {
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            border-radius: 50%;
            background-color: #6f42c1;
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
            border-color: #6f42c1;
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
            color: green;
        }
    </style>

    <!-- Styles -->
    @livewireStyles
</head>

<body>
    <div>
        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    @livewireScripts
</body>

</html>
