<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::orderBy('created_at', 'desc')->take(6)->get();
        return view('dashboard', compact('featuredProducts'));
    }
}
