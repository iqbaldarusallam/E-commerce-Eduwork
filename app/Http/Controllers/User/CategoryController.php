<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(string $slug) {
      $category = Category::where('slug', $slug)->firstOrFail();
      $products = Product::where('category_id', $category->id)->latest()->paginate(12);
      return view('user.categories.index', compact('category', 'products'));
    }
}
