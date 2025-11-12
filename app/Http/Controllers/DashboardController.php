<?php

namespace App\Http\Controllers;

use App\Models\Drawing;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $drawings = Drawing::with('fotos', 'user','files2d',)
            ->latest()
            ->paginate(12);

        return view('dashboard', compact('drawings'));
    }
}