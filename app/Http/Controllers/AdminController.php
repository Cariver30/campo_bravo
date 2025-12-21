<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Cocktail;
use App\Models\CocktailCategory;
use App\Models\Wine;
use App\Models\WineCategory;
use App\Models\Setting;
use App\Models\Popup; // Añadir el modelo Popup
use App\Models\WineType;
use App\Models\Region;
use App\Models\Grape;
use App\Models\FoodPairing;

use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function panel()
    {
        $categories = Category::with('dishes')->orderBy('order')->get();
        $dishes = Dish::all();
        $cocktails = Cocktail::all();
        $cocktailCategories = CocktailCategory::with('items')->orderBy('order')->get();
        $wines = Wine::all();
        $wineCategories = WineCategory::with('items')->orderBy('order')->get();
        $settings = Setting::first();
        $popups = Popup::all(); // Asegúrate de obtener todos los popups


        return view('admin', compact('categories', 'dishes', 'cocktails', 'cocktailCategories', 'wines', 'wineCategories', 'settings','popups'));
    }

    public function newAdminPanel()
    {
        $categories = Category::with('dishes')->orderBy('order')->get();
        $dishes = Dish::all();
        $cocktails = Cocktail::all();
        $cocktailCategories = CocktailCategory::with('items')->orderBy('order')->get();
        $wines = Wine::all();
        $wineCategories = WineCategory::with('items')->orderBy('order')->get();
        $settings = Setting::first();
        $popups = Popup::all(); // Asegúrate de obtener todos los popups
        $wineTypes = WineType::all();
$regions = Region::all();
$grapes = Grape::all();
$foodPairings = FoodPairing::all();

        


        return view('admin.admin-panel', compact('categories', 'dishes', 'cocktails', 'cocktailCategories', 'wines', 'wineCategories', 'settings','popups' ,'wineTypes','regions','grapes','foodPairings'));
    }

    public function updateBackground(Request $request)
    {
        $request->validate([
            'background_image_cover' => 'nullable|image',
            'background_image_menu' => 'nullable|image',
            'background_image_cocktails' => 'nullable|image',
            'background_image_wines' => 'nullable|image',
            'logo' => 'nullable|image',
            'text_color_cover' => 'nullable|string',
            'text_color_menu' => 'nullable|string',
            'text_color_cocktails' => 'nullable|string',
            'text_color_wines' => 'nullable|string',
            'card_opacity_cover' => 'nullable|numeric|between:0,1',
            'card_opacity_menu' => 'nullable|numeric|between:0,1',
            'card_opacity_cocktails' => 'nullable|numeric|between:0,1',
            'card_opacity_wines' => 'nullable|numeric|between:0,1',
            'font_family_cover' => 'nullable|string',
            'font_family_menu' => 'nullable|string',
            'font_family_cocktails' => 'nullable|string',
            'font_family_wines' => 'nullable|string',
            'button_color_cover' => 'nullable|string',
            'button_color_menu' => 'nullable|string',
            'button_color_cocktails' => 'nullable|string',
            'button_color_wines' => 'nullable|string',
            'button_font_size_cover' => 'nullable|integer',
            'category_name_bg_color_menu' => 'nullable|string',
            'category_name_text_color_menu' => 'nullable|string',
            'category_name_font_size_menu' => 'nullable|integer',
            'category_name_bg_color_cocktails' => 'nullable|string',
            'category_name_text_color_cocktails' => 'nullable|string',
            'category_name_font_size_cocktails' => 'nullable|integer',
            'category_name_bg_color_wines' => 'nullable|string',
            'category_name_text_color_wines' => 'nullable|string',
            'category_name_font_size_wines' => 'nullable|integer',
            'card_bg_color_menu' => 'nullable|string',
            'card_bg_color_cocktails' => 'nullable|string',
            'card_bg_color_wines' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'phone_number' => 'nullable|string',
            'business_hours' => 'nullable|string',
            'fixed_bottom_font_size' => 'nullable|integer',
            'fixed_bottom_font_color' => 'nullable|string'
        ]);

        $settings = Setting::first();

        if ($request->hasFile('background_image_cover')) {
            $path = $request->file('background_image_cover')->store('background_images', 'public');
            $settings->background_image_cover = $path;
        }
        if ($request->hasFile('background_image_menu')) {
            $path = $request->file('background_image_menu')->store('background_images', 'public');
            $settings->background_image_menu = $path;
        }
        if ($request->hasFile('background_image_cocktails')) {
            $path = $request->file('background_image_cocktails')->store('background_images', 'public');
            $settings->background_image_cocktails = $path;
        }
        if ($request->hasFile('background_image_wines')) {
            $path = $request->file('background_image_wines')->store('background_images', 'public');
            $settings->background_image_wines = $path;
        }
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $settings->logo = $path;
        }

        $settings->text_color_cover = $request->input('text_color_cover', $settings->text_color_cover);
        $settings->text_color_menu = $request->input('text_color_menu', $settings->text_color_menu);
        $settings->text_color_cocktails = $request->input('text_color_cocktails', $settings->text_color_cocktails);
        $settings->text_color_wines = $request->input('text_color_wines', $settings->text_color_wines);

        $settings->card_opacity_cover = $request->input('card_opacity_cover', $settings->card_opacity_cover);
        $settings->card_opacity_menu = $request->input('card_opacity_menu', $settings->card_opacity_menu);
        $settings->card_opacity_cocktails = $request->input('card_opacity_cocktails', $settings->card_opacity_cocktails);
        $settings->card_opacity_wines = $request->input('card_opacity_wines', $settings->card_opacity_wines);

        $settings->font_family_cover = $request->input('font_family_cover', $settings->font_family_cover);
        $settings->font_family_menu = $request->input('font_family_menu', $settings->font_family_menu);
        $settings->font_family_cocktails = $request->input('font_family_cocktails', $settings->font_family_cocktails);
        $settings->font_family_wines = $request->input('font_family_wines', $settings->font_family_wines);

        $settings->button_color_cover = $request->input('button_color_cover', $settings->button_color_cover);
        $settings->button_color_menu = $request->input('button_color_menu', $settings->button_color_menu);
        $settings->button_color_cocktails = $request->input('button_color_cocktails', $settings->button_color_cocktails);
        $settings->button_color_wines = $request->input('button_color_wines', $settings->button_color_wines);

        $settings->button_font_size_cover = $request->input('button_font_size_cover', $settings->button_font_size_cover);

        $settings->category_name_bg_color_menu = $request->input('category_name_bg_color_menu', $settings->category_name_bg_color_menu);
        $settings->category_name_text_color_menu = $request->input('category_name_text_color_menu', $settings->category_name_text_color_menu);
        $settings->category_name_font_size_menu = $request->input('category_name_font_size_menu', $settings->category_name_font_size_menu);

        $settings->category_name_bg_color_cocktails = $request->input('category_name_bg_color_cocktails', $settings->category_name_bg_color_cocktails);
        $settings->category_name_text_color_cocktails = $request->input('category_name_text_color_cocktails', $settings->category_name_text_color_cocktails);
        $settings->category_name_font_size_cocktails = $request->input('category_name_font_size_cocktails', $settings->category_name_font_size_cocktails);

        $settings->category_name_bg_color_wines = $request->input('category_name_bg_color_wines', $settings->category_name_bg_color_wines);
        $settings->category_name_text_color_wines = $request->input('category_name_text_color_wines', $settings->category_name_text_color_wines);
        $settings->category_name_font_size_wines = $request->input('category_name_font_size_wines', $settings->category_name_font_size_wines);

        $settings->card_bg_color_menu = $request->input('card_bg_color_menu', $settings->card_bg_color_menu);
        $settings->card_bg_color_cocktails = $request->input('card_bg_color_cocktails', $settings->card_bg_color_cocktails);
        $settings->card_bg_color_wines = $request->input('card_bg_color_wines', $settings->card_bg_color_wines);

        $settings->facebook_url = $request->input('facebook_url', $settings->facebook_url);
        $settings->twitter_url = $request->input('twitter_url', $settings->twitter_url);
        $settings->instagram_url = $request->input('instagram_url', $settings->instagram_url);
        $settings->phone_number = $request->input('phone_number', $settings->phone_number);
        $settings->business_hours = $request->input('business_hours', $settings->business_hours);

        $settings->fixed_bottom_font_size = $request->input('fixed_bottom_font_size', $settings->fixed_bottom_font_size);
        $settings->fixed_bottom_font_color = $request->input('fixed_bottom_font_color', $settings->fixed_bottom_font_color);

        $settings->save();

        return redirect()->route('admin.new-panel')->with('success', 'Configuraciones actualizadas con éxito.');
    }

    
    // app/Http/Controllers/AdminController.php


    public function indexPopups()
    {
        $popups = Popup::all();
        return view('popups.index', compact('popups'));
    }

    public function createPopup()
    {
        return view('popups.create');
    }

    // AdminController.php

    public function storePopup(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image',
            'view' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'active' => 'required|boolean',
            'repeat_days' => 'nullable|array'
        ]);

        $imagePath = $request->file('image')->store('popup_images', 'public');

        Popup::create([
            'title' => $request->title,
            'image' => $imagePath,
            'view' => $request->view,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'active' => $request->active,
            'repeat_days' => $request->repeat_days ? implode(',', $request->repeat_days) : null
        ]);

        return redirect()->route('admin.new-panel')->with('success', 'Pop-up creado con éxito.');
    }

    public function editPopup(Popup $popup)
    {
        return view('popups.edit', compact('popup'));
    }

    public function updatePopup(Request $request, Popup $popup)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image',
            'view' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'active' => 'required|boolean',
            'repeat_days' => 'nullable|array'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('popup_images', 'public');
            $popup->image = $imagePath;
        }

        $popup->title = $request->title;
        $popup->view = $request->view;
        $popup->start_date = $request->start_date;
        $popup->end_date = $request->end_date;
        $popup->active = $request->active;
        $popup->repeat_days = $request->repeat_days ? implode(',', $request->repeat_days) : null;

        $popup->save();

        return redirect()->route('admin.new-panel')->with('success', 'Pop-up actualizado con éxito.');
    }
    public function destroyPopup(Popup $popup)
    {
        $popup->delete();
        return redirect()->route('admin.new-panel')->with('success', 'Pop-up eliminado con éxito.');
    }

    public function toggleVisibility(Popup $popup)
    {
        $popup->update(['active' => !$popup->active]);
        return redirect()->route('admin.new-panel')->with('success', 'Visibilidad del pop-up actualizada.');
    }

}
