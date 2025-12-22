<?php

// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Category;
use App\Support\FeaturedGroupBuilder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function cover()
    {
        $settings = Setting::first();
        $featuredGroups = FeaturedGroupBuilder::build();

        return view('cover', compact('settings', 'featuredGroups'));
    }

    public function menu()
    {
        $settings = Setting::first();
        $categories = Category::with('dishes')->get();
        return view('menu', compact('settings', 'categories'));
    }
}
