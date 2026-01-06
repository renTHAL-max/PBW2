<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #fff; color: #000; line-height: 1.6; }
        .navbar { background: #fff; padding: 1.5rem 0; border-bottom: 1px solid #e0e0e0; }
        .navbar-brand { font-size: 1.5rem; font-weight: 300; color: #000; letter-spacing: 2px; text-decoration: none; }
        .nav-link { color: #000; font-weight: 300; margin: 0 1.5rem; font-size: 0.95rem; transition: opacity 0.3s; text-decoration: none; }
        .nav-link:hover { opacity: 0.6; }
        .hero { padding: 120px 0 80px; text-align: center; }
        .hero h1 { font-size: 3.5rem; font-weight: 300; letter-spacing: -2px; margin-bottom: 1rem; }
        .hero p { font-size: 1.1rem; color: #666; font-weight: 300; max-width: 600px; margin: 0 auto 3rem; }
        .car-grid { padding: 60px 0; }
        .car-item { margin-bottom: 4rem; }
        .car-image-wrapper { position: relative; overflow: hidden; background: #f5f5f5; aspect-ratio: 16/10; }
        .car-image { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
        .car-item:hover .car-image { transform: scale(1.05); }
        .car-info { padding: 2rem 0; }
        .car-name { font-size: 1.8rem; font-weight: 300; margin-bottom: 0.5rem; letter-spacing: -1px; }
        .car-category { color: #666; font-size: 0.9rem; font-weight: 300; margin-bottom: 1.5rem; }
        .car-price { font-size: 1.3rem; font-weight: 300; margin-bottom: 1rem; }
        .btn-book { background: #000; color: #fff; border: none; padding: 0.8rem 2.5rem; font-size: 0.9rem; font-weight: 300; transition: opacity 0.3s; width: 100%; }
        .btn-book:hover { opacity: 0.8; }
        .btn-booked { background: #f8f9fa; color: #adb5bd; border: 1px solid #dee2e6; padding: 0.8rem 2.5rem; font-size: 0.9rem; font-weight: 300; cursor: not-allowed; width: 100%; }
        .modal-content { border: none; border-radius: 0; }
        .modal-header { background: #000; color: #fff; border: none; padding: 2rem; }
        .modal-title { font-weight: 300; font-size: 1.5rem; letter-spacing: 1px; }
        .btn-close { filter: invert(1); }
        .modal-body { padding: 2rem; }
        .form-label { font-weight: 300; font-size: 0.9rem; color: #666; margin-bottom: 0.5rem; }
        .form-control, .form-select { border: 1px solid #ddd; border-radius: 0; padding: 0.8rem; font-weight: 300; }
        .form-control:focus, .form-select:focus { border-color: #000; box-shadow: none; }
        .booking-summary { background: #f9f9f9; padding: 2rem; margin-bottom: 2rem; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 1rem; font-size: 0.95rem; }
        .summary-label { color: #666; font-weight: 300; }
        .total-row { border-top: 1px solid #ddd; padding-top: 1rem; margin-top: 1rem; }
        .btn-submit { background: #000; color: #fff; border: none; padding: 1rem; width: 100%; font-size: 1rem; font-weight: 300; transition: opacity 0.3s; }
        .badge-status { font-size: 0.75rem; padding: 0.3rem 0.8rem; letter-spacing: 1px; font-weight: 300; text-transform: uppercase; margin-bottom: 1rem; display: inline-block; }
        .status-available { background: #f0fff0; color: #2e7d32; border: 1px solid #c8e6c9; }
        .status-booked { background: #fff5f5; color: #c62828; border: 1px solid #ffcdd2; }
        .status-maintenance { background: #fffdf0; color: #856404; border: 1px solid #ffeeba; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Rental Kendaraan</a>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#models">Model</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Tentang Kami</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <h1>Kendarai Impian Anda</h1>
            <p>Rasakan kemewahan dan performa dengan koleksi kendaraan premium kami</p>
        </div>
    </section>

    <section id="models" class="car-grid">
        <div class="container">
            <div class="row">
                @foreach($vehicles as $car)
                <div class="col-lg-6 car-item">
                    <div class="car-image-wrapper">
                        <img src="{{ $car->image ? asset('images/cars/'.$car->image) : 'https://via.placeholder.com/800x500?text='.$car->model }}" class="car-image">
                    </div>
                    <div class="car-info">
                        @if($car->status == 'tersedia')
                            <span class="badge-status status-available">Tersedia</span>
                        @elseif($car->status == 'dalam_perawatan')
                            <span class="badge-status status-maintenance">Perawatan</span>
                        @else
                            <span class="badge-status status-booked">Sudah Dipesan</span>
                        @endif

                        <h3 class="car-name">{{ $car->brand }} {{ $car->model }}</h3>
                        <p class="car-category">{{ $car->plate_number }}</p>
                        <div class="car-price">IDR {{ number_format($car->price_per_day, 0, ',', '.') }} / hari</div>
                        
                        @if($car->status == 'tersedia')
                            <button class="btn btn-book" onclick="openModal('{{ $car->model }}', {{ $car->price_per_day }}, '{{ $car->image ? asset('images/cars/'.$car->image) : '' }}')">Sewa Sekarang</button>
                        @else
                            <button class="btn btn-booked" disabled>TIDAK TERSEDIA</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">FORMULIR PENYEWAAN</h5>
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
                            <span class="summary-label">Durasi</span>
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
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. KTP </label>
                                <input type="text" class="form-control" name="id_card_number" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control" name="address" rows="2" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" name="birth_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Metode Pembayaran</label>
                                <select class="form-select" name="payment_method" required>
                                    <option value="">Pilih Pembayaran</option>
                                    <option value="cash">Tunai</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="debit">Kartu Debit</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="startDate" name="start_date" onchange="calcTotal()" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" id="endDate" name="end_date" onchange="calcTotal()" required>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-submit">KONFIRMASI PENYEWAAN</button>
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
            document.getElementById('modalPrice').textContent = 'Rp ' + price.toLocaleString('id-ID') + ' / hari';
            document.getElementById('modalImg').src = img ? img : 'https://via.placeholder.com/120x80';
            modal.show();
        }

        function calcTotal() {
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            if (start && end) {
                const d1 = new Date(start);
                const d2 = new Date(end);
                const days = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24));
                if (days > 0) {
                    const total = selectedPrice * days;
                    document.getElementById('duration').textContent = days + ' hari';
                    document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
                } else {
                    document.getElementById('duration').textContent = '-';
                    document.getElementById('total').textContent = 'Rp 0';
                }
            }
        }

        function submitBooking(e) {
            e.preventDefault();
            const formData = {
                name: e.target.name.value,
                phone: e.target.phone.value,
                email: e.target.email.value,
                id_card_number: e.target.id_card_number.value,
                address: e.target.address.value,
                birth_date: e.target.birth_date.value,
                vehicle_name: document.getElementById('modalName').textContent,
                start_date: document.getElementById('startDate').value,
                end_date: document.getElementById('endDate').value,
                payment_method: e.target.payment_method.value,
                _token: '{{ csrf_token() }}'
            };

            fetch('{{ route("booking.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Success') {
                    alert('Penyewaan Berhasil!');
                    modal.hide();
                    e.target.reset();
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => alert('Terjadi kesalahan.'));
            return false;
        }
    </script>
</body>
</html>