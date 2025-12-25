<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class MenuManagementController extends Controller
{
    public function categories()
    {
        $categories = Category::with(['dishes' => function ($query) {
            $query->orderBy('position')
                ->orderBy('id')
                ->with('recommendedDishes:id,name');
        }])->orderBy('order')->get();

        return response()->json([
            'categories' => $categories->map(fn (Category $category) => $this->serializeCategory($category)),
        ]);
    }

    public function storeDish(Request $request)
    {
        [$data, $relations] = $this->validateDish($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('dish_images', 'public');
        }

        $data['visible'] = $request->boolean('visible', true);
        $data['featured_on_cover'] = $request->boolean('featured_on_cover', false);
        $data['position'] = (Dish::where('category_id', $data['category_id'])->max('position') ?? 0) + 1;

        $dish = Dish::create($data);
        $this->syncRelations($dish, $relations);

        return response()->json([
            'message' => 'Plato creado correctamente.',
            'dish' => $this->serializeDish($dish->fresh('category')),
        ], Response::HTTP_CREATED);
    }

    public function updateDish(Request $request, Dish $dish)
    {
        [$data, $relations] = $this->validateDish($request, $dish->id);

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('dish_images', 'public');
            if ($dish->image) {
                Storage::disk('public')->delete($dish->image);
            }
            $data['image'] = $newPath;
        }

        $data['visible'] = $request->boolean('visible', true);
        $data['featured_on_cover'] = $request->boolean('featured_on_cover', false);

        $dish->update($data);
        $this->syncRelations($dish, $relations);

        return response()->json([
            'message' => 'Plato actualizado correctamente.',
            'dish' => $this->serializeDish($dish->fresh('category')),
        ]);
    }

    public function destroyDish(Dish $dish)
    {
        if ($dish->image) {
            Storage::disk('public')->delete($dish->image);
        }

        $dish->delete();

        return response()->json([
            'message' => 'Plato eliminado.',
        ]);
    }

    public function toggleDish(Dish $dish)
    {
        $dish->visible = !$dish->visible;
        $dish->save();

        return response()->json([
            'message' => 'Visibilidad actualizada.',
            'dish' => $this->serializeDish($dish),
        ]);
    }

    public function reorderDishes(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:dishes,id'],
        ]);

        foreach ($data['order'] as $index => $dishId) {
            Dish::where('id', $dishId)
                ->where('category_id', $data['category_id'])
                ->update(['position' => $index + 1]);
        }

        return response()->json([
            'message' => 'Orden actualizado.',
        ]);
    }

    protected function validateDish(Request $request, ?int $dishId = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'featured_on_cover' => ['nullable', 'boolean'],
            'visible' => ['nullable', 'boolean'],
            'image' => [$request->hasFile('image') ? 'required' : 'nullable', 'image', 'max:5120'],
            'recommended_dishes' => ['nullable', 'array'],
            'recommended_dishes.*' => [
                'integer',
                'exists:dishes,id',
                $dishId ? Rule::notIn([$dishId]) : null,
            ],
        ], [
            'description.required' => 'Falta la descripción del plato.',
        ]);

        unset($validated['image']);

        $relations = [
            'recommended_dishes' => collect($request->input('recommended_dishes', []))
                ->filter(fn ($id) => (int) $id !== (int) $dishId)
                ->unique()
                ->values()
                ->all(),
        ];

        return [$validated, $relations];
    }

    protected function syncRelations(Dish $dish, array $relations): void
    {
        if (array_key_exists('recommended_dishes', $relations)) {
            $dish->recommendedDishes()->sync($relations['recommended_dishes']);
        }
    }

    protected function serializeCategory(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'order' => $category->order,
            'dishes' => $category->dishes?->map(fn (Dish $dish) => $this->serializeDish($dish))->values() ?? [],
        ];
    }

    protected function serializeDish(Dish $dish): array
    {
        $recommended = $dish->relationLoaded('recommendedDishes')
            ? $dish->recommendedDishes->map(fn (Dish $recommendedDish) => [
                'id' => $recommendedDish->id,
                'name' => $recommendedDish->name,
            ])->values()
            : $dish->recommendedDishes()->get(['id', 'name'])->map(fn (Dish $recommendedDish) => [
                'id' => $recommendedDish->id,
                'name' => $recommendedDish->name,
            ])->values();

        return [
            'id' => $dish->id,
            'name' => $dish->name,
            'description' => $dish->description,
            'price' => (float) $dish->price,
            'category_id' => $dish->category_id,
            'category_name' => $dish->category?->name,
            'image' => $dish->image ? asset('storage/' . $dish->image) : null,
            'visible' => (bool) $dish->visible,
            'featured_on_cover' => (bool) $dish->featured_on_cover,
            'position' => $dish->position,
            'recommended_dishes' => $recommended,
        ];
    }
}
