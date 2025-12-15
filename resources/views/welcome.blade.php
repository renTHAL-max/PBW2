<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Kendaraan - Sistem Manajemen</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: #9ca3af;
            margin-bottom: 2rem;
            font-weight: 300;
        }

        .feature-card {
            background: rgba(26, 26, 26, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.2);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            padding: 1rem 3rem;
            font-size: 1.2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.5);
        }

        .btn-outline-custom {
            border: 2px solid #3b82f6;
            color: #3b82f6;
            padding: 1rem 3rem;
            font-size: 1.2rem;
            border-radius: 12px;
            font-weight: 600;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-3px);
        }

        .car-illustration {
            position: relative;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-30px);
            }
        }

        .stats-box {
            background: rgba(26, 26, 26, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
        }

        .stats-number {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-label {
            color: #9ca3af;
            font-size: 1rem;
            margin-top: 0.5rem;
        }

        .decorative-element {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .decorative-element-1 {
            top: -250px;
            right: -250px;
        }

        .decorative-element-2 {
            bottom: -250px;
            left: -250px;
        }

        .car-svg {
            width: 100%;
            max-width: 600px;
            height: auto;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 3rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="decorative-element decorative-element-1"></div>
    <div class="decorative-element decorative-element-2"></div>

    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">
                        Rental<br>Kendaraan
                    </h1>
                    <p class="hero-subtitle">
                        Sistem manajemen rental kendaraan yang powerful dan mudah digunakan. 
                        Kelola armada, pelanggan, dan transaksi dalam satu platform.
                    </p>
                    <div class="d-flex gap-3 mb-5">
                        <a href="/admin" class="btn btn-primary-custom">
                             <i class="fas fa-sign-in-alt me-2"></i>Masuk Dashboard
                        </a>
                        </a>
                        <a href="#features" class="btn btn-outline-custom">
                            <i class="fas fa-info-circle me-2"></i>Pelajari Lebih Lanjut
                        </a>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="stats-box">
                                <div class="stats-number">50+</div>
                                <div class="stats-label">Kendaraan</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stats-box">
                                <div class="stats-number">100+</div>
                                <div class="stats-label">Pelanggan</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stats-box">
                                <div class="stats-number">500+</div>
                                <div class="stats-label">Transaksi</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="car-illustration">
                        <svg class="car-svg" viewBox="0 0 800 600" xmlns="http://www.w3.org/2000/svg">
                            <!-- Car Body -->
                            <g id="car">
                                <!-- Main Body -->
                                <rect x="150" y="300" width="500" height="150" rx="20" fill="#3b82f6" opacity="0.9"/>
                                
                                <!-- Roof -->
                                <path d="M 250 300 L 300 200 L 500 200 L 550 300 Z" fill="#2563eb" opacity="0.9"/>
                                
                                <!-- Windows -->
                                <path d="M 270 290 L 310 220 L 450 220 L 490 290 Z" fill="#60a5fa" opacity="0.5"/>
                                
                                <!-- Front Light -->
                                <circle cx="630" cy="375" r="15" fill="#fbbf24"/>
                                
                                <!-- Back Light -->
                                <circle cx="170" cy="375" r="15" fill="#ef4444"/>
                                
                                <!-- Wheels -->
                                <circle cx="250" cy="460" r="50" fill="#1f2937" stroke="#3b82f6" stroke-width="5"/>
                                <circle cx="250" cy="460" r="30" fill="#374151"/>
                                <circle cx="550" cy="460" r="50" fill="#1f2937" stroke="#3b82f6" stroke-width="5"/>
                                <circle cx="550" cy="460" r="30" fill="#374151"/>
                                
                                <!-- Wheel Details -->
                                <circle cx="250" cy="460" r="15" fill="#60a5fa"/>
                                <circle cx="550" cy="460" r="15" fill="#60a5fa"/>
                                
                                <!-- Door Handle -->
                                <rect x="380" y="340" width="40" height="8" rx="4" fill="#1e40af"/>
                                
                                <!-- Side Mirror -->
                                <ellipse cx="140" cy="320" rx="20" ry="15" fill="#2563eb"/>
                            </g>
                            
                            <!-- Road -->
                            <rect x="0" y="510" width="800" height="90" fill="#111827" opacity="0.5"/>
                            <rect x="0" y="550" width="100" height="10" fill="#fbbf24" opacity="0.8"/>
                            <rect x="150" y="550" width="100" height="10" fill="#fbbf24" opacity="0.8"/>
                            <rect x="300" y="550" width="100" height="10" fill="#fbbf24" opacity="0.8"/>
                            <rect x="450" y="550" width="100" height="10" fill="#fbbf24" opacity="0.8"/>
                            <rect x="600" y="550" width="100" height="10" fill="#fbbf24" opacity="0.8"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="features" class="row mt-5 pt-5">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-4 fw-bold text-white mb-3">Fitur Unggulan</h2>
                    <p class="text-muted fs-5">Solusi lengkap untuk mengelola bisnis rental kendaraan Anda</p>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <h3 class="text-white mb-3">Manajemen Kendaraan</h3>
                        <p class="text-muted">
                            Kelola data kendaraan, maintenance, dan ketersediaan armada dengan mudah dan efisien.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-white mb-3">Database Pelanggan</h3>
                        <p class="text-muted">
                            Simpan dan akses data pelanggan dengan cepat. Track riwayat transaksi setiap pelanggan.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h3 class="text-white mb-3">Transaksi & Pembayaran</h3>
                        <p class="text-muted">
                            Proses transaksi rental dengan cepat. Generate invoice dan laporan keuangan otomatis.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="text-white mb-3">Dashboard & Analytics</h3>
                        <p class="text-muted">
                            Monitoring bisnis real-time dengan dashboard interaktif dan laporan detail.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-tools"></i>
                        </div>
                        <h3 class="text-white mb-3">Tracking Maintenance</h3>
                        <p class="text-muted">
                            Jadwalkan dan track maintenance kendaraan untuk menjaga performa armada.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="text-white mb-3">Role & Permission</h3>
                        <p class="text-muted">
                            Kelola akses user dengan sistem role dan permission yang fleksibel dan aman.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="row mt-5 pt-5 pb-4">
                <div class="col-12 text-center">
                    <p class="text-muted">
                        &copy; {{ date('Y') }} Rental Kendaraan. All rights reserved.
                    </p>
                    <p class="text-muted small">
                        Built with <i class="fas fa-heart text-danger"></i> using Laravel & Bootstrap
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Parallax effect for decorative elements
        document.addEventListener('mousemove', (e) => {
            const elements = document.querySelectorAll('.decorative-element');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            elements.forEach((element, index) => {
                const speed = (index + 1) * 20;
                const x = (mouseX * speed) - (speed / 2);
                const y = (mouseY * speed) - (speed / 2);
                element.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
    </script>
</body>
</html>