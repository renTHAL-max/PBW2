<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    public function index()
    {
        return view('pelanggan.index');
    }

    public function show($id)
    {
        return view('pelanggan.show');
    }
}
