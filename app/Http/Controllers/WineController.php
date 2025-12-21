<?php

namespace App\Http\Controllers;

use App\Models\Wine;
use App\Models\WineCategory;
use App\Models\Setting;
use App\Models\Popup;
use App\Models\WineType;
use App\Models\Region;
use App\Models\Grape;
use App\Models\FoodPairing;
use App\Models\Dish;

use Illuminate\Http\Request;

class WineController extends Controller
{
    public function index(Request $request)
{
    $settings = Setting::first();

    // 🔧 Detectar si hay filtros activos correctamente
    $hasFilter = $request->hasAny(['region', 'type', 'grape', 'max_price']);

    if ($hasFilter) {
        $wines = Wine::query()
            ->when($request->region, fn($q) => $q->where('region_id', $request->region))
            ->when($request->type, fn($q) => $q->where('type_id', $request->type))
            ->when($request->grape, fn($q) =>
                $q->whereHas('grapes', fn($g) => $g->where('grape_id', $request->grape))
            )
            ->when($request->max_price, fn($q) =>
                $q->where('price', '<=', $request->max_price)
            )
            ->where('visible', true)
            ->with(['type', 'region', 'grapes', 'dishes']) // ✅ Asegúrate de incluir 'dishes'
            ->get();

        return view('wine.index', [
            'settings' => $settings,
            'wines'    => $wines,
            'filters'  => true,
            'regions'  => Region::all(),
            'types'    => WineType::all(),
            'grapes'   => Grape::all(),
            'popups'   => Popup::where('active', 1)
                                ->where('view', 'wines')
                                ->whereDate('start_date', '<=', now())
                                ->whereDate('end_date', '>=', now())
                                ->get(),
        ]);
    }

    // Comportamiento sin filtros (mostrar por categoría)
    return view('wine.index', [
        'settings'       => $settings,
        'wineCategories' => WineCategory::with(['items' => function ($q) {
            $q->where('visible', true)
              ->with(['type', 'region', 'grapes', 'dishes']); // ✅ También aquí
        }])->get(),
        'filters'        => false,
        'regions'        => Region::all(),
        'types'          => WineType::all(),
        'grapes'         => Grape::all(),
        'popups'         => Popup::where('active', 1)
                                ->where('view', 'wines')
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
      

        return view('wine.create', compact(
            'categories',
            'types',
            'regions',
            'grapes',
            'foodPairings',
            'dishes' // ✅
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required',
            'description'       => 'nullable',
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
            'image'             => 'nullable|image'
        ]);
    
        $wine = new Wine($request->only([
            'name',
            'description',
            'price',
            'category_id',
            'type_id',
            'region_id',
        ]));
    
        if ($request->hasFile('image')) {
            $wine->image = $request->file('image')->store('wine_images', 'public');
        }
    
        $wine->save();
    
        // ✅ Sincronizar relaciones
        $wine->grapes()->sync($request->input('grapes', []));
        $wine->foodPairings()->sync($request->input('food_pairings', []));
        $wine->dishes()->sync($request->input('dishes', [])); // ✅ sincroniza los platos
    
        return redirect()->route('admin.new-panel', [
            'section' => 'wines-section',
            'open' => 'create-wine'
        ])
        ->with('success', 'Artículo de Vino creado con éxito');
    }
    

    public function edit(Wine $wine)
    {
        $categories = WineCategory::all();
        $types = WineType::all();
        $regions = Region::all();
        $grapes = Grape::all();
        $foodPairings = FoodPairing::all();
        $dishes = Dish::all();

        return view('wine.edit', compact(
            'wine',
            'categories',
            'types',
            'regions',
            'grapes',
            'foodPairings',
            'dishes'
        ));
    }

    public function update(Request $request, Wine $wine)
    {
        $request->validate([
            'name'              => 'required',
            'description'       => 'nullable',
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
            'image'             => 'nullable|image'    // ✅ faltaba la coma
        ]);
    
        $data = $request->only([
            'name',
            'description',
            'price',
            'category_id',
            'type_id',
            'region_id',
        ]);
    
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('wine_images', 'public');
        }
    
        $wine->update($data);
    
        // ✅ Sincronizamos relaciones pivot
        $wine->grapes()->sync($request->input('grapes', []));
        $wine->foodPairings()->sync($request->input('food_pairings', []));
        $wine->dishes()->sync($request->input('dishes', [])); // ✅ ahora guarda los platos recomendados
    
        return redirect()->route('admin.new-panel', ['section' => 'wines-section'])
            ->with('success', 'Artículo de Vino actualizado con éxito');
    }
    
    public function destroy(Wine $wine)
    {
        $wine->grapes()->detach();
        $wine->foodPairings()->detach();
        $wine->delete();

        return redirect()->route('admin.new-panel', ['section' => 'wines-section'])
            ->with('success', 'Artículo de Vino eliminado con éxito');
    }

    public function toggleVisibility(Wine $wine)
    {
        $wine->visible = !$wine->visible;
        $wine->save();

        return redirect()->route('admin.new-panel', ['section' => 'wines-section'])
            ->with('success', 'Visibilidad del artículo actualizada');
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
