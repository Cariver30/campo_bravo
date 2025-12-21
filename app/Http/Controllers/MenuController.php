<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use App\Models\Popup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function index()
{
    $settings = Setting::first();

    $categories = Category::with(['dishes' => function($q) {
        $q->where('visible', true)->with('wines'); // ✅ Cargar vinos asociados a cada plato
    }])->get();

    $popups = Popup::where('active', 1)
                ->where('view', 'menu')
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->get();

    return view('menu', compact('settings', 'categories', 'popups'));
}

}

