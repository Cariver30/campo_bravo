<?php

namespace App\Http\Controllers;

use App\Models\CocktailCategory;
use Illuminate\Http\Request;

class CocktailCategoryController extends Controller
{
    public function index()
    {
        $categories = CocktailCategory::orderBy('order')->get();
        return view('cocktail.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('cocktail.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $order = (CocktailCategory::max('order') ?? 0) + 1;

        CocktailCategory::create([
            'name' => $request->name,
            'order' => $order,
        ]);

        return redirect()->route('admin.new-panel', ['section' => 'cocktails-section', 'open' => 'create-cocktail-category'])->with('success', 'Categoría de cocktail creada con éxito.');
    }

    public function edit(CocktailCategory $cocktailCategory)
    {
        return view('cocktail.categories.edit', compact('cocktailCategory'));
    }

    public function update(Request $request, CocktailCategory $cocktailCategory)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $cocktailCategory->update($request->only('name'));

        return redirect()->route('admin.new-panel', ['section' => 'cocktails-section', 'open' => 'cocktail-category-list'])->with('success', 'Categoría de cocktail actualizada con éxito.');
    }

    public function destroy(CocktailCategory $cocktailCategory)
    {
        $cocktailCategory->delete();

        return redirect()->route('admin.new-panel', ['section' => 'cocktails-section', 'open' => 'cocktail-category-list'])->with('success', 'Categoría de cocktail eliminada con éxito.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:cocktail_categories,id',
        ]);

        foreach ($data['order'] as $index => $id) {
            CocktailCategory::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
