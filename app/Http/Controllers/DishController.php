<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Extra;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DishController extends Controller
{
    public function index()
{
    $dishes = Dish::with('wines')->get(); // <- importante
    return view('dishes.index', compact('dishes'));
}


    public function create()
    {
        $categories = Category::with('subcategories')->orderBy('order')->get();
        $allDishes = Dish::orderBy('name')->get(['id', 'name']);
        $availableExtras = Extra::orderBy('name')->forView('menu')->get();

        return view('dishes.create', compact('categories', 'allDishes', 'availableExtras'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'featured_on_cover' => ['nullable', 'boolean'],
            'recommended_dishes' => ['nullable', 'array'],
            'recommended_dishes.*' => ['integer', 'exists:dishes,id'],
            'extra_ids' => ['nullable', 'array'],
            'extra_ids.*' => ['integer', 'exists:extras,id'],
        ], [
            'description.required' => 'Falta la descripción del plato.',
        ]);

        $data = $validated;
        $data['subcategory_id'] = $this->validateSubcategoryHierarchy($request->input('subcategory_id'), (int) $data['category_id']);
        $data['visible'] = $request->boolean('visible', true);
        $data['featured_on_cover'] = $request->boolean('featured_on_cover');
        $data['position'] = $this->nextPosition((int) $data['category_id'], $data['subcategory_id']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('dish_images', 'public');
        }

        $dish = Dish::create($data);
        $dish->recommendedDishes()->sync(
            $this->collectRecommendedDishIds($request, $dish)
        );
        $dish->extras()->sync($this->collectExtraIds($request));

        return redirect()->route('admin.new-panel', [
            'section' => 'menu-section',
            'open' => 'menu-create',
            'expand' => 'dish-categories',
        ])->with('success', 'Plato creado exitosamente y visible en el menú.');
    }

    public function edit(Dish $dish)
    {
        $categories = Category::with('subcategories')->orderBy('order')->get();
        $allDishes = Dish::orderBy('name')->get(['id', 'name']);
        $availableExtras = Extra::orderBy('name')->forView('menu')->get();
        $dish->loadMissing('recommendedDishes:id,name', 'extras:id,name');

        return view('dishes.edit', compact('dish', 'categories', 'allDishes', 'availableExtras'));
    }

    public function update(Request $request, Dish $dish)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id'],
            'image' => 'nullable|image|max:5000',
            'featured_on_cover' => ['nullable', 'boolean'],
            'recommended_dishes' => ['nullable', 'array'],
            'recommended_dishes.*' => ['integer', 'exists:dishes,id'],
            'extra_ids' => ['nullable', 'array'],
            'extra_ids.*' => ['integer', 'exists:extras,id'],
        ], [
            'description.required' => 'Falta la descripción del plato.',
        ]);

        $data = $validated;
        $data['subcategory_id'] = $this->validateSubcategoryHierarchy($request->input('subcategory_id'), (int) $data['category_id']);
        $data['visible'] = $request->boolean('visible', true);
        $data['featured_on_cover'] = $request->boolean('featured_on_cover');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('dish_images', 'public');
        }

        if ($dish->category_id !== (int) $data['category_id'] || $dish->subcategory_id !== $data['subcategory_id']) {
            $data['position'] = $this->nextPosition((int) $data['category_id'], $data['subcategory_id']);
        }

        $dish->update($data);
        $dish->recommendedDishes()->sync(
            $this->collectRecommendedDishIds($request, $dish)
        );
        $dish->extras()->sync($this->collectExtraIds($request));

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
            'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id'],
            'order' => 'required|array',
            'order.*' => 'integer|exists:dishes,id',
        ]);

        foreach ($data['order'] as $index => $dishId) {
            Dish::where('id', $dishId)
                ->where('category_id', $data['category_id'])
                ->when(
                    $data['subcategory_id'] ?? null,
                    fn ($query, $subcategoryId) => $query->where('subcategory_id', $subcategoryId),
                    fn ($query) => $query->whereNull('subcategory_id')
                )
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

    private function collectExtraIds(Request $request): array
    {
        return collect($request->input('extra_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();
    }

    private function validateSubcategoryHierarchy($subcategoryId, int $categoryId): ?int
    {
        if (! $subcategoryId) {
            return null;
        }

        $id = (int) $subcategoryId;
        $exists = Subcategory::where('id', $id)
            ->where('category_id', $categoryId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'subcategory_id' => 'La subcategoría seleccionada no pertenece a la categoría elegida.',
            ]);
        }

        return $id;
    }

    private function nextPosition(int $categoryId, ?int $subcategoryId = null): int
    {
        $max = Dish::where('category_id', $categoryId)
            ->when($subcategoryId, fn ($query) => $query->where('subcategory_id', $subcategoryId))
            ->when(is_null($subcategoryId), fn ($query) => $query->whereNull('subcategory_id'))
            ->max('position');

        return (int) $max + 1;
    }
}
