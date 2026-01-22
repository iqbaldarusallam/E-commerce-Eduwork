<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    //
    public function index()
    {
        $categories = Category::select('id', 'name', 'slug', 'description')->limit(10)->get();
        $products = Product::where('active', true)->with('category', 'images')->latest()->limit(5)->get();
        return view('user.home.index', compact('categories', 'products'));
    }

      public function contact()
    {
        return view('user.contact.index');
    }

    public function about()
    {
        return view('user.about.index');
    }

}
