<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->ordered()->get();

        $categories = Category::where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        $featuredProducts = Product::with(['category', 'primaryImage', 'images'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $bestSellers = Product::with(['category', 'primaryImage'])
            ->where('status', 'active')
            ->where('is_best_seller', true)
            ->take(4)
            ->get();

        $reviews = Review::with(['user', 'product'])
            ->where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        $storeSettings = StoreSetting::pluck('value', 'key');

        return view('home', compact(
            'banners',
            'categories',
            'featuredProducts',
            'bestSellers',
            'reviews',
            'storeSettings'
        ));
    }

    public function about()
    {
        $storeSettings = StoreSetting::pluck('value', 'key');

        return view('about', compact('storeSettings'));
    }

    public function contact()
    {
        $storeSettings = StoreSetting::pluck('value', 'key');

        return view('contact', compact('storeSettings'));
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:1000',
        ]);

        return back()->with('success', 'Pesan Anda telah berhasil dikirim! Tim Desy Florist akan segera menghubungi Anda.');
    }
}
