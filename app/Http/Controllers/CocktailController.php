<?php

namespace App\Http\Controllers;

use App\Models\Cocktail;
use App\Models\CocktailCategory;
use App\Models\Extra;
use App\Models\Setting;
use App\Models\Dish;
use App\Models\Popup;
use Illuminate\Http\Request;

class CocktailController extends Controller
{
    public function index()
{
    $settings = Setting::first();
    $cocktailCategories = CocktailCategory::with(['items' => function ($query) {
        $query->where('visible', true)
            ->with([
                'dishes:id,name',
                'extras' => function ($extraQuery) {
                    $extraQuery->select('extras.id', 'name', 'price', 'description', 'active');
                },
            ])
            ->orderBy('position');
    }])->get();
    $popups = Popup::where('active', 1)
                    ->where('view', 'cocktails')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->get();

    // Añade este log para depuración
    \Log::info('Datos para la vista de cocktails', compact('settings', 'cocktailCategories', 'popups'));

    return view('cocktail.index', compact('settings', 'cocktailCategories', 'popups'));
}

    public function create()
    {
        $categories = CocktailCategory::all();
        $dishes = Dish::orderBy('name')->get();
        $availableExtras = Extra::orderBy('name')->forView('cocktails')->get();

        return view('cocktail.create', compact('categories','dishes','availableExtras'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:cocktail_categories,id',
            'image' => 'nullable|image',
            'featured_on_cover' => ['nullable', 'boolean'],
            'dishes' => ['nullable', 'array'],
            'dishes.*' => ['integer', 'exists:dishes,id'],
            'extra_ids' => ['nullable','array'],
            'extra_ids.*' => ['integer','exists:extras,id'],
        ]);

        $cocktail = new Cocktail($validated);
        $cocktail->visible = $request->boolean('visible', true);

        if ($request->hasFile('image')) {
            $cocktail->image = $request->file('image')->store('cocktail_images', 'public');
        }

        $cocktail->featured_on_cover = $request->boolean('featured_on_cover');
        $cocktail->save();
        $cocktail->dishes()->sync($request->input('dishes', []));
        $cocktail->extras()->sync($request->input('extra_ids', []));

        return redirect()->route('cocktails.edit', $cocktail)->with('success', 'Cóctel creado con éxito.');
    }

    public function edit(Cocktail $cocktail)
    {
        $categories = CocktailCategory::all();
        $dishes = Dish::orderBy('name')->get();
        $availableExtras = Extra::orderBy('name')->forView('cocktails')->get();

        return view('cocktail.edit', compact('cocktail', 'categories','dishes','availableExtras'));
    }

    public function update(Request $request, Cocktail $cocktail)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:cocktail_categories,id',
            'image' => 'nullable|image',
            'featured_on_cover' => ['nullable', 'boolean'],
            'dishes' => ['nullable','array'],
            'dishes.*' => ['integer','exists:dishes,id'],
            'extra_ids' => ['nullable','array'],
            'extra_ids.*' => ['integer','exists:extras,id'],
        ]);

        $data = $validated;
        $data['visible'] = $request->boolean('visible', true);
        $data['featured_on_cover'] = $request->boolean('featured_on_cover');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cocktail_images', 'public');
        }

        $cocktail->update($data);
        $cocktail->dishes()->sync($request->input('dishes', []));
        $cocktail->extras()->sync($request->input('extra_ids', []));

        return redirect()->route('cocktails.edit', $cocktail)->with('success', 'Cóctel actualizado con éxito.');
    }

    public function destroy(Cocktail $cocktail)
    {
        $cocktail->delete();
        return redirect()->route('admin.new-panel', [
            'section' => 'cocktails-section',
            'open' => 'cocktail-create',
            'expand' => 'cocktail-categories',
        ])->with('success', 'Artículo de Cocktail eliminado con éxito');
    }

    public function toggleVisibility(Cocktail $cocktail)
    {
        $cocktail->visible = !$cocktail->visible;
        $cocktail->save();

        return redirect()->route('admin.new-panel', [
            'section' => 'cocktails-section',
            'open' => 'cocktail-create',
            'expand' => 'cocktail-categories',
        ])->with('success', 'Visibilidad del artículo de Cocktail actualizada');
    }

    public function toggleFeatured(Cocktail $cocktail)
    {
        $cocktail->featured_on_cover = !$cocktail->featured_on_cover;
        $cocktail->save();

        return redirect()->route('admin.new-panel', [
            'section' => 'cocktails-section',
            'open' => 'cocktail-create',
            'expand' => 'cocktail-categories',
        ])->with('success', 'Destacado en portada actualizado.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:cocktail_categories,id',
            'order' => 'required|array',
            'order.*' => 'integer|exists:cocktails,id',
        ]);

        foreach ($data['order'] as $index => $id) {
            Cocktail::where('id', $id)
                ->where('category_id', $data['category_id'])
                ->update(['position' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
