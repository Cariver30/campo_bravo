<?php

namespace App\Http\Controllers;

use App\Models\Cocktail;
use App\Models\CocktailCategory;
use App\Models\Setting;
use App\Models\Popup;
use Illuminate\Http\Request;

class CocktailController extends Controller
{
    public function index()
{
    $settings = Setting::first();
    $cocktailCategories = CocktailCategory::with('items')->get();
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
        return view('cocktail.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:cocktail_categories,id',
            'image' => 'nullable|image'
        ]);

        $cocktail = new Cocktail($request->all());

        if ($request->hasFile('image')) {
            $cocktail->image = $request->file('image')->store('cocktail_images', 'public');
        }

        $cocktail->save();

        return redirect()->route('admin.new-panel', ['section' => 'cocktails-section', 'open' => 'create-cocktail'])->with('success', 'Artículo de Cocktail creado con éxito');
    }

    public function edit(Cocktail $cocktail)
    {
        $categories = CocktailCategory::all();
        return view('cocktail.edit', compact('cocktail', 'categories'));
    }

    public function update(Request $request, Cocktail $cocktail)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:cocktail_categories,id',
            'image' => 'nullable|image'
        ]);

        $data = $request->all();

        // Asegúrate de convertir el valor del checkbox visible a un valor booleano
        $data['visible'] = $request->has('visible') ? true : false;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cocktail_images', 'public');
        }

        $cocktail->update($data);

        return redirect()->route('admin.new-panel', ['section' => 'cocktails-section'])->with('success', 'Artículo de Cocktail actualizado con éxito');
    }

    public function destroy(Cocktail $cocktail)
    {
        $cocktail->delete();
        return redirect()->route('admin.new-panel', ['section' => 'cocktails-section'])->with('success', 'Artículo de Cocktail eliminado con éxito');
    }

    public function toggleVisibility(Cocktail $cocktail)
    {
        $cocktail->visible = !$cocktail->visible;
        $cocktail->save();

        return redirect()->route('admin.new-panel', ['section' => 'cocktails-section'])->with('success', 'Visibilidad del artículo de Cocktail actualizada');
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
