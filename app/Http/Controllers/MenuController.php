<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Popup;
use App\Models\Setting;

class MenuController extends Controller
{
    public function index()
    {
        $settings = Setting::first();

        $categories = Category::with([
                'dishes' => function ($query) {
                    $query->where('visible', true)
                        ->with('wines')
                        ->orderBy('position');
                },
            ])
            ->orderBy('order')
            ->get();

        $popups = Popup::where('active', 1)
            ->where('view', 'menu')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();

        return view('menu', compact('settings', 'categories', 'popups'));
    }
}
