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
        $data = $request->validate([
            'name' => 'required',
            'show_on_cover' => ['nullable', 'boolean'],
            'cover_title' => ['nullable', 'string', 'max:255'],
            'cover_subtitle' => ['nullable', 'string', 'max:255'],
        ]);

        $data['order'] = (CocktailCategory::max('order') ?? 0) + 1;
        $data['show_on_cover'] = $request->boolean('show_on_cover');

        CocktailCategory::create($data);

        return redirect()->route('admin.new-panel', [
            'section' => 'cocktails-section',
            'open' => 'cocktail-create',
            'expand' => 'cocktail-categories',
        ])->with('success', 'Categoría de cocktail creada con éxito.');
    }

    public function edit(CocktailCategory $cocktailCategory)
    {
        return view('cocktail.categories.edit', compact('cocktailCategory'));
    }

    public function update(Request $request, CocktailCategory $cocktailCategory)
    {
        $data = $request->validate([
            'name' => 'required',
            'show_on_cover' => ['nullable', 'boolean'],
            'cover_title' => ['nullable', 'string', 'max:255'],
            'cover_subtitle' => ['nullable', 'string', 'max:255'],
        ]);

        $data['show_on_cover'] = $request->boolean('show_on_cover');

        $cocktailCategory->update($data);

        return redirect()->route('admin.new-panel', [
            'section' => 'cocktails-section',
            'open' => 'cocktail-create',
            'expand' => 'cocktail-categories',
        ])->with('success', 'Categoría de cocktail actualizada con éxito.');
    }

    public function destroy(CocktailCategory $cocktailCategory)
    {
        $cocktailCategory->delete();

        return redirect()->route('admin.new-panel', [
            'section' => 'cocktails-section',
            'open' => 'cocktail-create',
            'expand' => 'cocktail-categories',
        ])->with('success', 'Categoría de cocktail eliminada con éxito.');
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

    public function toggleCover(CocktailCategory $cocktailCategory)
    {
        $cocktailCategory->show_on_cover = !$cocktailCategory->show_on_cover;
        if ($cocktailCategory->show_on_cover && !$cocktailCategory->cover_title) {
            $cocktailCategory->cover_title = $cocktailCategory->name;
        }
        $cocktailCategory->save();

        return redirect()->route('admin.new-panel', [
            'section' => 'cocktails-section',
            'open' => 'cocktail-create',
            'expand' => 'cocktail-categories',
        ])->with('success', 'Categoría actualizada en la portada.');
    }

    public function updateFeaturedItems(Request $request, CocktailCategory $cocktailCategory)
    {
        $data = $request->validate([
            'featured_items' => ['nullable', 'array'],
            'featured_items.*' => ['integer', 'exists:cocktails,id'],
        ]);

        $ids = collect($data['featured_items'] ?? []);
        $cocktailCategory->load('items');

        foreach ($cocktailCategory->items as $item) {
            $item->featured_on_cover = $ids->contains($item->id);
            $item->save();
        }

        return redirect()->route('admin.new-panel', [
            'section' => 'cocktails-section',
            'open' => 'cocktail-create',
            'expand' => 'cocktail-categories',
        ])->with('success', 'Cócteles destacados actualizados.');
    }
}
