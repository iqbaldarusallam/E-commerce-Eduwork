<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(){
      return view('user.home.index');
    }

    // New contact method
    public function contact()
    {
        return view('user.contact.index');
    }
}
