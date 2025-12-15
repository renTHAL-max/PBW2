<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Kendaraan;
// use App\Models\TransaksiRental; // Uncomment ketika model sudah dibuat

class DashboardController extends Controller
{
    public function index()
    {
        // Stats untuk dashboard
        $stats = [
            'total_kendaraan' => 50, // Ganti dengan: Kendaraan::count() ketika model sudah ada
            'kendaraan_tersedia' => 35,
            'total_pelanggan' => Pelanggan::count(),
            'total_transaksi' => 500, // Ganti dengan: TransaksiRental::count()
        ];

        // Top 5 Kendaraan Terpopuler (dummy data untuk sementara)
        $topKendaraan = [
            [
                'merek' => 'Toyota',
                'model' => 'Agya',
                'plat_nomor' => 'B 1234 ABC',
                'total_disewa' => 0,
                'harga_per_hari' => 250000,
                'status' => 'tersedia'
            ],
            [
                'merek' => 'Toyota',
                'model' => 'Avanza',
                'plat_nomor' => 'B 5678 XYZ',
                'total_disewa' => 0,
                'harga_per_hari' => 350000,
                'status' => 'tersedia'
            ],
            [
                'merek' => 'Daihatsu',
                'model' => 'Xenia',
                'plat_nomor' => 'B 9012 DEF',
                'total_disewa' => 0,
                'harga_per_hari' => 300000,
                'status' => 'tersedia'
            ],
            [
                'merek' => 'Toyota',
                'model' => 'Fortuner',
                'plat_nomor' => 'B 3456 GHI',
                'total_disewa' => 0,
                'harga_per_hari' => 600000,
                'status' => 'tersedia'
            ],
            [
                'merek' => 'Honda',
                'model' => 'Jazz',
                'plat_nomor' => 'B 7890 JKL',
                'total_disewa' => 0,
                'harga_per_hari' => 400000,
                'status' => 'tersedia'
            ]
        ];

        // Chart data untuk pendapatan 7 hari terakhir (dummy)
        $chartData = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData['labels'][] = $date->format('d M');
            $chartData['data'][] = rand(0, 5); // Random untuk demo
        }

        return view('dashboard.index', compact('stats', 'topKendaraan', 'chartData'));
    }
}
