<?php

// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Category;
use App\Models\CoverCarouselItem;
use App\Support\FeaturedGroupBuilder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function cover()
    {
        $settings = Setting::first();
        $featuredGroups = FeaturedGroupBuilder::build();
        $carouselItems = CoverCarouselItem::visible()->orderBy('position')->get();

        return view('cover', compact('settings', 'featuredGroups', 'carouselItems'));
    }

    public function menu()
    {
        $settings = Setting::first();
        $categories = Category::with('dishes')->get();
        return view('menu', compact('settings', 'categories'));
    }
}
