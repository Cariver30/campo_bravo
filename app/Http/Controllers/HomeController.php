<?php

// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function cover()
    {
        $settings = Setting::first();
        return view('cover', compact('settings'));
    }

    public function menu()
    {
        $settings = Setting::first();
        $categories = Category::with('dishes')->get();
        return view('menu', compact('settings', 'categories'));
    }
}
