<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function index()
{
    $dishes = Dish::with('wines')->get(); // <- importante
    return view('dishes.index', compact('dishes'));
}


    public function create()
    {
        $categories = Category::all();
        $allDishes = Dish::orderBy('name')->get(['id', 'name']);

        return view('dishes.create', compact('categories', 'allDishes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'featured_on_cover' => ['nullable', 'boolean'],
            'recommended_dishes' => ['nullable', 'array'],
            'recommended_dishes.*' => ['integer', 'exists:dishes,id'],
        ], [
            'description.required' => 'Falta la descripción del plato.',
        ]);

        $data = $validated;
        $data['visible'] = $request->boolean('visible', true);
        $data['featured_on_cover'] = $request->boolean('featured_on_cover');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('dish_images', 'public');
        }

        $dish = Dish::create($data);
        $dish->recommendedDishes()->sync(
            $this->collectRecommendedDishIds($request, $dish)
        );

        return redirect()->route('admin.new-panel', [
            'section' => 'menu-section',
            'open' => 'menu-create',
            'expand' => 'dish-categories',
        ])->with('success', 'Plato creado exitosamente y visible en el menú.');
    }

    public function edit(Dish $dish)
    {
        $categories = Category::all();
        $allDishes = Dish::orderBy('name')->get(['id', 'name']);
        $dish->loadMissing('recommendedDishes:id,name');

        return view('dishes.edit', compact('dish', 'categories', 'allDishes'));
    }

    public function update(Request $request, Dish $dish)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'image' => 'nullable|image|max:5000',
            'featured_on_cover' => ['nullable', 'boolean'],
            'recommended_dishes' => ['nullable', 'array'],
            'recommended_dishes.*' => ['integer', 'exists:dishes,id'],
        ], [
            'description.required' => 'Falta la descripción del plato.',
        ]);

        $data = $validated;
        $data['visible'] = $request->boolean('visible', true);
        $data['featured_on_cover'] = $request->boolean('featured_on_cover');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('dish_images', 'public');
        }

        $dish->update($data);
        $dish->recommendedDishes()->sync(
            $this->collectRecommendedDishIds($request, $dish)
        );

        return redirect()->route('dishes.edit', $dish)->with('success', 'Plato actualizado exitosamente.');
    }

    public function destroy(Dish $dish)
    {
        $dish->delete();
        return redirect()->route('admin.new-panel', [
            'section' => 'menu-section',
            'open' => 'menu-create',
            'expand' => 'dish-categories',
        ])->with('success', 'Plato eliminado exitosamente.');
    }

    public function toggleVisibility(Dish $dish)
    {
        $dish->visible = !$dish->visible;
        $dish->save();

        return redirect()->route('admin.new-panel', [
            'section' => 'menu-section',
            'open' => 'menu-create',
            'expand' => 'dish-categories',
        ])->with('success', 'Visibilidad del plato actualizada.');
    }

    public function toggleFeatured(Dish $dish)
    {
        $dish->featured_on_cover = !$dish->featured_on_cover;
        $dish->save();

        return redirect()->route('admin.new-panel', [
            'section' => 'menu-section',
            'open' => 'menu-create',
            'expand' => 'dish-categories',
        ])->with('success', 'Estado destacado actualizado.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'order' => 'required|array',
            'order.*' => 'integer|exists:dishes,id',
        ]);

        foreach ($data['order'] as $index => $dishId) {
            Dish::where('id', $dishId)
                ->where('category_id', $data['category_id'])
                ->update(['position' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    private function collectRecommendedDishIds(Request $request, ?Dish $dish = null): array
    {
        return collect($request->input('recommended_dishes', []))
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0 && (! $dish || $dish->id !== $id))
            ->unique()
            ->values()
            ->all();
    }
}
