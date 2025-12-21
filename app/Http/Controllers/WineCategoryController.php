<?php

namespace App\Http\Controllers;

use App\Models\WineCategory;
use Illuminate\Http\Request;

class WineCategoryController extends Controller
{
    // Mostrar todas las categorías de vino (aunque ya no se usen en frontend)
    public function index()
    {
        $categories = WineCategory::orderBy('order')->get();
        return view('wine.categories.index', compact('categories'));
    }

    // Formulario para crear una nueva categoría
    public function create()
    {
        return view('wine.categories.create');
    }

    // Guardar nueva categoría
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:wine_categories,name',
        ]);

        $order = (WineCategory::max('order') ?? 0) + 1;

        WineCategory::create([
            'name' => $request->name,
            'order' => $order,
        ]);

        return redirect()->route('admin.new-panel', ['section' => 'wine-categories'])
            ->with('success', 'Categoría creada con éxito');
    }

    // Formulario para editar una categoría existente
    public function edit(WineCategory $wineCategory)
    {
        return view('wine.categories.edit', compact('wineCategory'));
    }

    // Actualizar una categoría existente
    public function update(Request $request, WineCategory $wineCategory)
    {
        $request->validate([
            'name' => 'required|unique:wine_categories,name,' . $wineCategory->id,
        ]);

        $wineCategory->update($request->only('name'));

        return redirect()->route('admin.new-panel', ['section' => 'wine-categories'])
            ->with('success', 'Categoría actualizada con éxito');
    }

    // Eliminar una categoría
    public function destroy(WineCategory $wineCategory)
    {
        $wineCategory->delete();

        return redirect()->route('admin.new-panel', ['section' => 'wine-categories'])
            ->with('success', 'Categoría eliminada con éxito');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:wine_categories,id',
        ]);

        foreach ($data['order'] as $index => $id) {
            WineCategory::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
