<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Extra;
use App\Models\FoodPairing;
use App\Models\Grape;
use App\Models\Popup;
use App\Models\Region;
use App\Models\Setting;
use App\Models\Wine;
use App\Models\WineCategory;
use App\Models\WineType;

use Illuminate\Http\Request;

class WineController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::first();
        $filterCategories = WineCategory::orderBy('order')->get();

        $filters = [
            'category' => $request->input('category'),
            'region' => $request->input('region'),
            'type' => $request->input('type'),
            'grape' => $request->input('grape'),
            'max_price' => $request->input('max_price'),
            'q' => $request->input('q'),
        ];

        $hasFilter = collect($filters)
            ->filter(fn ($value) => !is_null($value) && $value !== '')
            ->isNotEmpty();

        if ($hasFilter) {
            $wines = Wine::query()
                ->when($filters['category'], fn ($q, $category) => $q->where('category_id', $category))
                ->when($filters['region'], fn ($q, $region) => $q->where('region_id', $region))
                ->when($filters['type'], fn ($q, $type) => $q->where('type_id', $type))
                ->when($filters['grape'], function ($q, $grape) {
                    $q->whereHas('grapes', fn ($g) => $g->where('grape_id', $grape));
                })
                ->when($filters['max_price'], fn ($q, $price) => $q->where('price', '<=', $price))
                ->when($filters['q'], fn ($q, $term) => $q->where('name', 'like', '%' . $term . '%'))
                ->where('visible', true)
                ->with([
                    'type',
                    'region',
                    'grapes',
                    'dishes',
                    'extras' => function ($extraQuery) {
                        $extraQuery->select('extras.id', 'name', 'price', 'description', 'active');
                    },
                ])
                ->orderBy('category_id')
                ->orderBy('position')
                ->orderBy('name')
                ->get();

            return view('cava.index', [
                'settings' => $settings,
                'wines' => $wines,
                'filters' => true,
                'wineCategories' => $filterCategories,
                'regions' => Region::all(),
                'types' => WineType::all(),
                'grapes' => Grape::all(),
                'filterCategories' => $filterCategories,
                'selectedFilters' => $filters,
                'popups' => Popup::where('active', 1)
                    ->whereIn('view', ['coffee', 'wines', 'cava'])
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->get(),
            ]);
        }

        return view('cava.index', [
            'settings' => $settings,
            'wineCategories' => WineCategory::with(['items' => function ($q) {
                $q->where('visible', true)
                    ->with([
                        'type',
                        'region',
                        'grapes',
                        'dishes',
                        'extras' => function ($extraQuery) {
                            $extraQuery->select('extras.id', 'name', 'price', 'description', 'active');
                        },
                    ])
                    ->orderBy('position')
                    ->orderBy('id');
            }])->orderBy('order')->get(),
            'filters' => false,
            'regions' => Region::all(),
            'types' => WineType::all(),
            'grapes' => Grape::all(),
            'filterCategories' => $filterCategories,
            'selectedFilters' => $filters,
            'wines' => collect(),
            'popups' => Popup::where('active', 1)
                ->whereIn('view', ['coffee', 'wines', 'cava'])
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->get(),
        ]);
    }


    public function create()
    {
        $categories = WineCategory::all();
        $types = WineType::all();
        $regions = Region::all();
        $grapes = Grape::all();
        $foodPairings = FoodPairing::all();
        $dishes = Dish::all(); // ✅
        $availableExtras = Extra::orderBy('name')->forView('coffee')->get();

        return view('wine.create', compact(
            'categories',
            'types',
            'regions',
            'grapes',
            'foodPairings',
            'dishes',
            'availableExtras'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'required|string',
            'price'             => 'required|numeric',
            'category_id'       => 'required|exists:wine_categories,id',
            'type_id'           => 'nullable|exists:wine_types,id',
            'region_id'         => 'nullable|exists:regions,id',
            'grapes'            => 'nullable|array',
            'grapes.*'          => 'exists:grapes,id',
            'food_pairings'     => 'nullable|array',
            'food_pairings.*'   => 'exists:food_pairings,id',
            'dishes'            => 'nullable|array',
            'dishes.*'          => 'exists:dishes,id', // ✅ Validación de platos
            'extra_ids'         => ['nullable','array'],
            'extra_ids.*'       => ['integer','exists:extras,id'],
            'image'             => 'nullable|image',
            'featured_on_cover' => ['nullable', 'boolean'],
        ], [
            'description.required' => 'Falta la descripción del producto.',
        ]);
    
        $wine = new Wine($validated);
        $wine->visible = $request->boolean('visible', true);
    
        if ($request->hasFile('image')) {
            $wine->image = $request->file('image')->store('wine_images', 'public');
        }
    
        $wine->featured_on_cover = $request->boolean('featured_on_cover');
        $wine->save();

        // ✅ Sincronizar relaciones
        $wine->grapes()->sync($request->input('grapes', []));
        $wine->foodPairings()->sync($request->input('food_pairings', []));
        $wine->dishes()->sync($request->input('dishes', [])); // ✅ sincroniza los platos
        $wine->extras()->sync($request->input('extra_ids', []));

    
        return redirect()->route('wines.edit', $wine)->with('success', 'Bebida creada con éxito.');
    }
    

    public function edit(Wine $wine)
    {
        $categories = WineCategory::all();
        $types = WineType::all();
        $regions = Region::all();
        $grapes = Grape::all();
        $foodPairings = FoodPairing::all();
        $dishes = Dish::all();
        $availableExtras = Extra::orderBy('name')->forView('coffee')->get();

        return view('wine.edit', compact(
            'wine',
            'categories',
            'types',
            'regions',
            'grapes',
            'foodPairings',
            'dishes',
            'availableExtras'
        ));
    }

    public function update(Request $request, Wine $wine)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'required|string',
            'price'             => 'required|numeric',
            'category_id'       => 'required|exists:wine_categories,id',
            'type_id'           => 'nullable|exists:wine_types,id',
            'region_id'         => 'nullable|exists:regions,id',
            'grapes'            => 'nullable|array',
            'grapes.*'          => 'exists:grapes,id',
            'food_pairings'     => 'nullable|array',
            'food_pairings.*'   => 'exists:food_pairings,id',
            'dishes'            => 'nullable|array',   // ✅ validación
            'dishes.*'          => 'exists:dishes,id', // ✅ validación
            'extra_ids'         => ['nullable','array'],
            'extra_ids.*'       => ['integer','exists:extras,id'],
            'image'             => 'nullable|image',    // ✅ faltaba la coma
            'featured_on_cover' => ['nullable', 'boolean'],
        ], [
            'description.required' => 'Falta la descripción del producto.',
        ]);
    
        $data = $validated;
    
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('wine_images', 'public');
        }
    
        $wine->update($data + [
            'visible' => $request->boolean('visible', true),
            'featured_on_cover' => $request->boolean('featured_on_cover'),
        ]);

        // ✅ Sincronizamos relaciones pivot
        $wine->grapes()->sync($request->input('grapes', []));
        $wine->foodPairings()->sync($request->input('food_pairings', []));
        $wine->dishes()->sync($request->input('dishes', [])); // ✅ ahora guarda los platos recomendados
        $wine->extras()->sync($request->input('extra_ids', []));

    
        return redirect()->route('wines.edit', $wine)->with('success', 'Bebida actualizada con éxito.');
    }
    
    public function destroy(Wine $wine)
    {
        $wine->grapes()->detach();
        $wine->foodPairings()->detach();
        $wine->delete();

        return redirect()->route('admin.new-panel', [
            'section' => 'wines-section',
            'open' => 'wine-create',
            'expand' => 'wine-categories',
        ])->with('success', 'Bebida de café eliminada con éxito');
    }

    public function toggleVisibility(Wine $wine)
    {
        $wine->visible = !$wine->visible;
        $wine->save();

        return redirect()->route('admin.new-panel', [
            'section' => 'wines-section',
            'open' => 'wine-create',
            'expand' => 'wine-categories',
        ])->with('success', 'Visibilidad del artículo actualizada');
    }

    public function toggleFeatured(Wine $wine)
    {
        $wine->featured_on_cover = !$wine->featured_on_cover;
        $wine->save();

        return redirect()->route('admin.new-panel', [
            'section' => 'wines-section',
            'open' => 'wine-create',
            'expand' => 'wine-categories',
        ])->with('success', 'Destacado en portada actualizado');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:wine_categories,id',
            'order' => 'required|array',
            'order.*' => 'integer|exists:wines,id',
        ]);

        foreach ($data['order'] as $index => $id) {
            Wine::where('id', $id)
                ->where('category_id', $data['category_id'])
                ->update(['position' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
