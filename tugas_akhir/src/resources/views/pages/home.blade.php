@extends('pages.templates.base')


@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero">
        <div class="hero-overlay"></div>
        <div class="container text-center hero-content">
            <h1 class="display-3 fw-bold">SHELLA BEAUTY SALON</h1>
            <p class="lead mb-4">Mewujudkan kecantikan alami Anda dengan sentuhan profesional</p>
            <a href="{{ route('services') }}" class="btn btn-lg btn-outline-light">Pesan Sekarang</a>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Layanan Kami</h2>
                <p>Kami menyediakan berbagai perawatan untuk meningkatkan kecantikan Anda.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0">
                        <img src="https://images.unsplash.com/photo-1634449571010-02389ed0f9b0?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="card-img-top" alt="Perawatan Rambut">
                        <div class="card-body">
                            <h5 class="card-title">Perawatan Rambut</h5>
                            <p class="card-text">Potongan, pewarnaan, dan perawatan rambut dengan teknologi terbaru.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0">
                        <img src="https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="card-img-top" alt="Perawatan Wajah">
                        <div class="card-body">
                            <h5 class="card-title">Perawatan Wajah</h5>
                            <p class="card-text">Facial dan perawatan kulit untuk mendapatkan tampilan yang bersinar.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0">
                        <img src="https://images.unsplash.com/photo-1604654894610-df63bc536371?q=80&w=1374&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                            class="card-img-top" alt="Manikur & Pedikur">
                        <div class="card-body">
                            <h5 class="card-title">Manikur & Pedikur</h5>
                            <p class="card-text">Perawatan kuku untuk tampilan yang rapi dan menarik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="/assets/img/backgrounds/background_salon2.jpeg" class="img-fluid rounded"
                        class="img-fluid rounded" alt="Tentang Salon">
                </div>
                <div class="col-md-6">
                    <h2 class="section-title">Tentang Kami</h2>
                    <p>Salon Cantik telah berdiri sejak 2010 dan berkomitmen memberikan perawatan terbaik. Kami percaya
                        bahwa kecantikan merupakan bentuk ekspresi diri yang unik.</p>
                    <p>Tim profesional kami siap membantu Anda meraih penampilan terbaik dengan pelayanan yang ramah dan
                        berkualitas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Hubungi Kami</h2>
                <p>Silakan hubungi kami atau kunjungi langsung lokasi kami.</p>
            </div>

            <div class="row g-5 align-items-start">
                <!-- Kiri: Info Salon -->
                <div class="col-md-6">
                    <h4 class="mb-3">SHELLA BEAUTY SALON</h4>
                    <p><i class="bi bi-geo-alt-fill me-2"></i>Jl.aria jaya santika, rt.03 rw.05, dekat lapangan semeru, Kab. Tangerang, Tigaraksa, Banten. Tangerang.</p>
                    <p><i class="bi bi-telephone-fill me-2"></i>+62 812-3456-7890</p>
                    <p><i class="bi bi-envelope-fill me-2"></i>shellasalon@gmail.com</p>

                    <div class="mt-4">
                        <h5 class="mb-2">Sosial Media</h5>
                        <a href="https://instagram.com/shellasalon" target="_blank" class="me-3">
                            <i class="bi bi-instagram fs-4"></i>
                        </a>
                        <a href="https://wa.me/6281234567890" target="_blank" class="me-3">
                            <i class="bi bi-whatsapp fs-4"></i>
                        </a>
                        <a href="https://facebook.com/shellasalon" target="_blank">
                            <i class="bi bi-facebook fs-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Kanan: Maps -->
                <div class="col-md-6">
                    <div class="ratio ratio-4x3 rounded shadow">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d426.0385295621095!2d106.46645689557832!3d-6.259593940794181!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sJl.aria%20jaya%20santika%2C%20rt.03%20rw.05%2C%20dekat%20lapangan%20semeru%2C%20Kab.%20Tangerang%2C%20Tigaraksa%2C%20Banten.%20Tangerang.!5e1!3m2!1sid!2sid!4v1751807227104!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
