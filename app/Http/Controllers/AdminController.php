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
use App\Support\FeaturedGroupBuilder;
use App\Models\LoyaltyReward;
use App\Models\LoyaltyCustomer;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;


class AdminController extends Controller
{
    protected function sanitizeLabel(?string $value, ?string $fallback = null): ?string
    {
        $value = is_string($value) ? trim($value) : null;
        if ($value === null || $value === '') {
            return $fallback;
        }

        return $value;
    }

    protected function resolveTabLabel(?string $value, ?string $fallback): ?string
    {
        $value = is_string($value) ? trim($value) : null;
        if ($value === '') {
            return null;
        }

        return $value ?? $fallback;
    }

    public function panel()
    {
        $categories = Category::with('dishes')->orderBy('order')->get();
        $dishes = Dish::with('category')->get();
        $cocktails = Cocktail::with('category')->get();
        $cocktailCategories = CocktailCategory::with('items')->orderBy('order')->get();
        $wines = Wine::with('category')->get();
        $wineCategories = WineCategory::with('items')->orderBy('order')->get();
        $settings = Setting::first();
        $popups = Popup::all(); // Asegúrate de obtener todos los popups


        return view('admin', compact('categories', 'dishes', 'cocktails', 'cocktailCategories', 'wines', 'wineCategories', 'settings','popups'));
    }

    public function newAdminPanel()
    {
        $categories = Category::with('dishes')->orderBy('order')->get();
        $dishes = Dish::with('category')->get();
        $cocktails = Cocktail::with('category')->get();
        $cocktailCategories = CocktailCategory::with('items')->orderBy('order')->get();
        $wines = Wine::with('category')->get();
        $wineCategories = WineCategory::with('items')->orderBy('order')->get();
        $settings = Setting::first();
        $popups = Popup::all(); // Asegúrate de obtener todos los popups
        $wineTypes = WineType::all();
$regions = Region::all();
$grapes = Grape::all();
$foodPairings = FoodPairing::all();
        $featuredGroups = FeaturedGroupBuilder::build(true);
        $loyaltyRewards = LoyaltyReward::orderBy('points_required')->get();
        $servers = User::where('role', 'server')->orderBy('name')->get();
        $loyaltyCustomers = LoyaltyCustomer::orderByDesc('points')->limit(8)->get();

        


        return view('admin.admin-panel', compact(
            'categories',
            'dishes',
            'cocktails',
            'cocktailCategories',
            'wines',
            'wineCategories',
            'settings',
            'popups',
            'wineTypes',
            'regions',
            'grapes',
            'foodPairings',
            'featuredGroups',
            'loyaltyRewards',
            'servers',
            'loyaltyCustomers'
        ));
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
            'fixed_bottom_font_color' => 'nullable|string',
            'button_label_menu' => 'nullable|string|max:255',
            'button_label_cocktails' => 'nullable|string|max:255',
            'button_label_wines' => 'nullable|string|max:255',
            'button_label_events' => 'nullable|string|max:255',
            'button_label_vip' => 'nullable|string|max:255',
            'button_label_reservations' => 'nullable|string|max:255',
            'tab_label_menu' => 'nullable|string|max:255',
            'tab_label_cocktails' => 'nullable|string|max:255',
            'tab_label_wines' => 'nullable|string|max:255',
            'tab_label_events' => 'nullable|string|max:255',
            'tab_label_loyalty' => 'nullable|string|max:255',
        ]);

        $settings = Setting::first();

        $buttonLabelMenu = $this->sanitizeLabel($request->input('button_label_menu', $settings->button_label_menu));
        $buttonLabelCocktails = $this->sanitizeLabel($request->input('button_label_cocktails', $settings->button_label_cocktails));
        $buttonLabelWines = $this->sanitizeLabel($request->input('button_label_wines', $settings->button_label_wines));
        $buttonLabelEvents = $this->sanitizeLabel($request->input('button_label_events', $settings->button_label_events));

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

        if ($request->hasFile('cover_gallery_image_1')) {
            $settings->cover_gallery_image_1 = $request->file('cover_gallery_image_1')->store('cover_gallery', 'public');
        }
        if ($request->hasFile('cover_gallery_image_2')) {
            $settings->cover_gallery_image_2 = $request->file('cover_gallery_image_2')->store('cover_gallery', 'public');
        }
        if ($request->hasFile('cover_gallery_image_3')) {
            $settings->cover_gallery_image_3 = $request->file('cover_gallery_image_3')->store('cover_gallery', 'public');
        }
        if ($request->hasFile('menu_hero_image')) {
            $settings->menu_hero_image = $request->file('menu_hero_image')->store('hero_images', 'public');
        }
        if ($request->hasFile('cocktail_hero_image')) {
            $settings->cocktail_hero_image = $request->file('cocktail_hero_image')->store('hero_images', 'public');
        }
        if ($request->hasFile('coffee_hero_image')) {
            $settings->coffee_hero_image = $request->file('coffee_hero_image')->store('hero_images', 'public');
        }
        if ($request->hasFile('cta_image_menu')) {
            $settings->cta_image_menu = $request->file('cta_image_menu')->store('cta_images', 'public');
        }
        if ($request->hasFile('cta_image_cafe')) {
            $settings->cta_image_cafe = $request->file('cta_image_cafe')->store('cta_images', 'public');
        }
        if ($request->hasFile('cta_image_cocktails')) {
            $settings->cta_image_cocktails = $request->file('cta_image_cocktails')->store('cta_images', 'public');
        }
        if ($request->hasFile('cta_image_events')) {
            $settings->cta_image_events = $request->file('cta_image_events')->store('cta_images', 'public');
        }
        if ($request->hasFile('cta_image_reservations')) {
            $settings->cta_image_reservations = $request->file('cta_image_reservations')->store('cta_images', 'public');
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
        $settings->card_bg_color_cover = $request->input('card_bg_color_cover', $settings->card_bg_color_cover);
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

        $settings->button_label_menu = $buttonLabelMenu;
        $settings->button_label_cocktails = $buttonLabelCocktails;
        $settings->button_label_wines = $buttonLabelWines;
        $settings->button_label_events = $buttonLabelEvents;
        $settings->button_label_vip = $request->input('button_label_vip', $settings->button_label_vip);
        $settings->button_label_reservations = $request->input('button_label_reservations', $settings->button_label_reservations);
        if (Schema::hasColumn('settings', 'tab_label_menu')) {
            $settings->tab_label_menu = $this->resolveTabLabel($request->input('tab_label_menu'), $buttonLabelMenu);
            $settings->tab_label_cocktails = $this->resolveTabLabel($request->input('tab_label_cocktails'), $buttonLabelCocktails);
            $settings->tab_label_wines = $this->resolveTabLabel($request->input('tab_label_wines'), $buttonLabelWines);
            $settings->tab_label_events = $this->resolveTabLabel($request->input('tab_label_events'), $buttonLabelEvents ?: 'Eventos');
            $settings->tab_label_loyalty = $this->resolveTabLabel($request->input('tab_label_loyalty'), $settings->tab_label_loyalty ?? 'Fidelidad');
            $settings->show_tab_menu = $request->boolean('show_tab_menu');
            $settings->show_tab_cocktails = $request->boolean('show_tab_cocktails');
            $settings->show_tab_wines = $request->boolean('show_tab_wines');
            $settings->show_tab_events = $request->boolean('show_tab_events');
            $settings->show_tab_campaigns = $request->boolean('show_tab_campaigns');
            $settings->show_tab_popups = $request->boolean('show_tab_popups');
            $settings->show_tab_loyalty = $request->boolean('show_tab_loyalty');
        }

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
