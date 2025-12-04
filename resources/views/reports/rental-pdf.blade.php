<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rental</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>LAPORAN TRANSAKSI RENTAL KENDARAAN</h1>
    
    @if($dateFrom || $dateUntil)
        <p>
            Periode: 
            {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') : '-' }} 
            s/d 
            {{ $dateUntil ? \Carbon\Carbon::parse($dateUntil)->format('d/m/Y') : '-' }}
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Durasi</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rentals as $index => $rental)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $rental->rental_code }}</td>
                <td>{{ $rental->customer->name }}</td>
                <td>{{ $rental->start_date->format('d/m/Y') }}</td>
                <td>{{ $rental->duration_days }} hari</td>
                <td class="text-right">Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</td>
                <td>{{ ucfirst($rental->status) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="footer">
                <td colspan="5" class="text-right">TOTAL:</td>
                <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top: 30px; font-size: 10px;">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </p>
</body>
</html>