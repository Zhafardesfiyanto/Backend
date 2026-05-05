<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheatLog; // Jangan lupa import modelnya!
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Pastikan NAMA fungsinya 'index' dan ada tanda kurung kurawal { }
    public function index()
    {
        // Ambil data pelanggaran terbaru
        $violations = CheatLog::with(['student'])->latest()->get();
        
        // Arahkan ke file dashboard.blade.php
        return view('admin.dashboard', compact('violations'));
    }
}