<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #fff;
            color: #000;
            line-height: 1.6;
        }

        .navbar {
            background: #fff;
            padding: 1.5rem 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 300;
            color: #000;
            letter-spacing: 2px;
        }

        .nav-link {
            color: #000;
            font-weight: 300;
            margin: 0 1.5rem;
            font-size: 0.95rem;
            transition: opacity 0.3s;
        }

        .nav-link:hover {
            opacity: 0.6;
        }

        .btn-admin {
            background: #000;
            color: #fff;
            border: none;
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 300;
            transition: opacity 0.3s;
        }

        .btn-admin:hover {
            opacity: 0.8;
        }

        .hero {
            padding: 120px 0 80px;
            text-align: center;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 300;
            letter-spacing: -2px;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.1rem;
            color: #666;
            font-weight: 300;
            max-width: 600px;
            margin: 0 auto 3rem;
        }

        .car-grid {
            padding: 60px 0;
        }

        .car-item {
            margin-bottom: 4rem;
            cursor: pointer;
        }

        .car-image-wrapper {
            position: relative;
            overflow: hidden;
            background: #f5f5f5;
            aspect-ratio: 16/10;
        }

        .car-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .car-item:hover .car-image {
            transform: scale(1.05);
        }

        .car-info {
            padding: 2rem 0;
        }

        .car-name {
            font-size: 1.8rem;
            font-weight: 300;
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }

        .car-category {
            color: #666;
            font-size: 0.9rem;
            font-weight: 300;
            margin-bottom: 1.5rem;
        }

        .car-specs {
            display: flex;
            gap: 2rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: #666;
        }

        .car-price {
            font-size: 1.3rem;
            font-weight: 300;
            margin-bottom: 1rem;
        }

        .btn-book {
            background: #000;
            color: #fff;
            border: none;
            padding: 0.8rem 2.5rem;
            font-size: 0.9rem;
            font-weight: 300;
            transition: opacity 0.3s;
        }

        .btn-book:hover {
            opacity: 0.8;
        }

        .modal-content {
            border: none;
            border-radius: 0;
        }

        .modal-header {
            background: #000;
            color: #fff;
            border: none;
            padding: 2rem;
        }

        .modal-title {
            font-weight: 300;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }

        .btn-close {
            filter: invert(1);
        }

        .modal-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 300;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 1px solid #ddd;
            border-radius: 0;
            padding: 0.8rem;
            font-weight: 300;
        }

        .form-control:focus, .form-select:focus {
            border-color: #000;
            box-shadow: none;
        }

        .booking-summary {
            background: #f9f9f9;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .summary-label {
            color: #666;
            font-weight: 300;
        }

        .summary-value {
            font-weight: 400;
        }

        .total-row {
            border-top: 1px solid #ddd;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .total-row .summary-value {
            font-size: 1.3rem;
        }

        .btn-submit {
            background: #000;
            color: #fff;
            border: none;
            padding: 1rem;
            width: 100%;
            font-size: 1rem;
            font-weight: 300;
            transition: opacity 0.3s;
        }

        .btn-submit:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">RENTAL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#models">Models</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a href="/dashboard" class="btn btn-admin">Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <h1>Drive Your Dreams</h1>
            <p>Experience luxury and performance with our premium vehicle collection</p>
        </div>
    </section>

    <section id="models" class="car-grid">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 car-item" onclick="openModal('Toyota Avanza', 350000, 'https://images.unsplash.com/photo-1619767886558-efdc259cde1a?w=800')">
                    <div class="car-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1619767886558-efdc259cde1a?w=800" class="car-image" alt="Toyota Avanza">
                    </div>
                    <div class="car-info">
                        <h3 class="car-name">Toyota Avanza</h3>
                        <p class="car-category">MPV</p>
                        <div class="car-specs">
                            <span>7 Seats</span>
                            <span>Manual</span>
                            <span>Gasoline</span>
                        </div>
                        <div class="car-price">Rp 350.000 / day</div>
                        <button class="btn btn-book">Book Now</button>
                    </div>
                </div>

                <div class="col-lg-6 car-item" onclick="openModal('Honda Jazz', 400000, 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800')">
                    <div class="car-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800" class="car-image" alt="Honda Jazz">
                    </div>
                    <div class="car-info">
                        <h3 class="car-name">Honda Jazz</h3>
                        <p class="car-category">Hatchback</p>
                        <div class="car-specs">
                            <span>5 Seats</span>
                            <span>Automatic</span>
                            <span>Gasoline</span>
                        </div>
                        <div class="car-price">Rp 400.000 / day</div>
                        <button class="btn btn-book">Book Now</button>
                    </div>
                </div>

                <div class="col-lg-6 car-item" onclick="openModal('Toyota Innova', 500000, 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800')">
                    <div class="car-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800" class="car-image" alt="Toyota Innova">
                    </div>
                    <div class="car-info">
                        <h3 class="car-name">Toyota Innova</h3>
                        <p class="car-category">MPV</p>
                        <div class="car-specs">
                            <span>7 Seats</span>
                            <span>Automatic</span>
                            <span>Diesel</span>
                        </div>
                        <div class="car-price">Rp 500.000 / day</div>
                        <button class="btn btn-book">Book Now</button>
                    </div>
                </div>

                <div class="col-lg-6 car-item" onclick="openModal('Honda HR-V', 550000, 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=800')">
                    <div class="car-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=800" class="car-image" alt="Honda HR-V">
                    </div>
                    <div class="car-info">
                        <h3 class="car-name">Honda HR-V</h3>
                        <p class="car-category">SUV</p>
                        <div class="car-specs">
                            <span>5 Seats</span>
                            <span>CVT</span>
                            <span>Gasoline</span>
                        </div>
                        <div class="car-price">Rp 550.000 / day</div>
                        <button class="btn btn-book">Book Now</button>
                    </div>
                </div>

                <div class="col-lg-6 car-item" onclick="openModal('Mitsubishi Xpander', 375000, 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800')">
                    <div class="car-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800" class="car-image" alt="Mitsubishi Xpander">
                    </div>
                    <div class="car-info">
                        <h3 class="car-name">Mitsubishi Xpander</h3>
                        <p class="car-category">MPV</p>
                        <div class="car-specs">
                            <span>7 Seats</span>
                            <span>Manual</span>
                            <span>Gasoline</span>
                        </div>
                        <div class="car-price">Rp 375.000 / day</div>
                        <button class="btn btn-book">Book Now</button>
                    </div>
                </div>

                <div class="col-lg-6 car-item" onclick="openModal('Toyota Fortuner', 750000, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800')">
                    <div class="car-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800" class="car-image" alt="Toyota Fortuner">
                    </div>
                    <div class="car-info">
                        <h3 class="car-name">Toyota Fortuner</h3>
                        <p class="car-category">SUV</p>
                        <div class="car-specs">
                            <span>7 Seats</span>
                            <span>Automatic</span>
                            <span>Diesel</span>
                        </div>
                        <div class="car-price">Rp 750.000 / day</div>
                        <button class="btn btn-book">Book Now</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">BOOKING</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="booking-summary">
                        <div class="d-flex align-items-center mb-4">
                            <img id="modalImg" src="" style="width: 120px; height: 80px; object-fit: cover; margin-right: 1.5rem;">
                            <div>
                                <h5 id="modalName" style="font-weight: 300; font-size: 1.5rem; margin-bottom: 0.3rem;"></h5>
                                <p id="modalPrice" style="color: #666; margin: 0; font-size: 0.95rem;"></p>
                            </div>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Duration</span>
                            <span class="summary-value" id="duration">-</span>
                        </div>
                        <div class="summary-row total-row">
                            <span class="summary-label">Total</span>
                            <span class="summary-value" id="total">Rp 0</span>
                        </div>
                    </div>

                    <form onsubmit="return submitBooking(event)">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ID Number</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" rows="2" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" onchange="calcTotal()" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" id="endDate" onchange="calcTotal()" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Driver</label>
                                <select class="form-select" id="driver" onchange="calcTotal()">
                                    <option value="0">Without Driver</option>
                                    <option value="150000">With Driver (+Rp 150.000/day)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Payment Method</label>
                                <select class="form-select" required>
                                    <option value="">Select Payment</option>
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Bank Transfer</option>
                                    <option value="card">Credit Card</option>
                                </select>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-submit">CONFIRM BOOKING</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let selectedPrice = 0;
        let modal;

        document.addEventListener('DOMContentLoaded', function() {
            modal = new bootstrap.Modal(document.getElementById('bookingModal'));
        });

        function openModal(name, price, img) {
            selectedPrice = price;
            document.getElementById('modalName').textContent = name;
            document.getElementById('modalPrice').textContent = 'Rp ' + price.toLocaleString('id-ID') + ' / day';
            document.getElementById('modalImg').src = img;
            modal.show();
        }

        function calcTotal() {
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            const driver = parseInt(document.getElementById('driver').value);

            if (start && end) {
                const d1 = new Date(start);
                const d2 = new Date(end);
                const days = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24));

                if (days > 0) {
                    const total = (selectedPrice + driver) * days;
                    document.getElementById('duration').textContent = days + ' days';
                    document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
                }
            }
        }

        function submitBooking(e) {
            e.preventDefault();
            alert('Booking submitted successfully!');
            modal.hide();
            e.target.reset();
            return false;
        }
    </script>
</body>
</html>