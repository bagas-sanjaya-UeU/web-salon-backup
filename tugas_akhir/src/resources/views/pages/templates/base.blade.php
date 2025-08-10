<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SHELLA BEAUTY SALON</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        /* Global Style */
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Hero Section */
        .hero {
            background: url('/assets/img/backgrounds/background_salon.jpeg') no-repeat center center/cover;
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            color: #fff;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        /* Section Titles */
        .section-title {
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* Card Hover Effect */
        .card img {
            transition: transform 0.3s ease;
        }

        .card:hover img {
            transform: scale(1.05);
        }

        /* Footer */
        footer {
            background: #333;
            color: #fff;
        }

        .card-custom {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .card-custom:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            background-color: #fff;
        }

        .card-title {
            font-weight: 700;
        }

        .card-text {
            color: #666;
        }

        .btn-custom {
            background-color: #007bff;
            border: none;
            border-radius: 50px;
            transition: background-color 0.3s ease;
            color: #fff;
        }

        .btn-custom:hover {
            background-color: #0056b3;
            color: #fff;
        }

        .section-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 30px;
            margin-top: 60px;
        }

        .detail-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .detail-card:hover {
            transform: translateY(-5px);
        }

        .detail-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .detail-content {
            padding: 20px;
        }

        .service-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .service-description {
            font-size: 1rem;
            color: #666;
            margin-bottom: 20px;
        }

        .service-price {
            font-size: 1.5rem;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .btn-add-cart {
            background-color: #28a745;
            border: none;
            border-radius: 50px;
            padding: 10px 30px;
            transition: background-color 0.3s ease;
            color: #fff;
        }

        .btn-add-cart:hover {
            background-color: #218838;
        }

        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>

<body>

    @include('pages.templates.navbar')

    @yield('content')

    @include('pages.templates.footer')



    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    @yield('script')
</body>

</html>
