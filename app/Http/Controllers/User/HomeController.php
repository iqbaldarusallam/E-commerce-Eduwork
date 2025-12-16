<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;

class HomeController extends Controller
{
    //
    public function index()
    {
        $categories = Category::select('id', 'name')->take(10)->get();

        return view('user.home.index', compact('categories'));
    }
}
