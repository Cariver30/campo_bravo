<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dish;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class MenuManagementController extends Controller
{
    public function categories()
    {
        $categories = Category::with([
            'dishes' => function ($query) {
                $query->whereNull('subcategory_id')
                    ->orderBy('position')
                    ->orderBy('id')
                    ->with([
                        'recommendedDishes:id,name',
                        'extras:id,name,price,view_scope',
                    ]);
            },
            'subcategories' => function ($query) {
                $query->orderBy('order')->with([
                    'dishes' => function ($dishQuery) {
                        $dishQuery->orderBy('position')
                            ->orderBy('id')
                            ->with([
                                'recommendedDishes:id,name',
                                'extras:id,name,price,view_scope',
                            ]);
                    },
                ]);
            },
        ])->orderBy('order')->get();

        return response()->json([
            'categories' => $categories->map(fn (Category $category) => $this->serializeCategory($category)),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $data = $this->validateCategory($request);
        $data['order'] = (Category::max('order') ?? 0) + 1;

        $category = Category::create($data);

        return response()->json([
            'message' => 'Categoría creada correctamente.',
            'category' => $this->serializeCategory($category),
        ], Response::HTTP_CREATED);
    }

    public function updateCategory(Request $request, Category $category)
    {
        $data = $this->validateCategory($request);
        $category->update($data);

        return response()->json([
            'message' => 'Categoría actualizada correctamente.',
            'category' => $this->serializeCategory($category->fresh('dishes')),
        ]);
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Categoría eliminada correctamente.',
        ]);
    }

    public function reorderCategories(Request $request)
    {
        $data = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:categories,id'],
        ]);

        foreach ($data['order'] as $index => $categoryId) {
            Category::where('id', $categoryId)->update(['order' => $index + 1]);
        }

        return response()->json([
            'message' => 'Orden de categorías actualizado.',
        ]);
    }

    public function storeSubcategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $data['order'] = (Subcategory::where('category_id', $data['category_id'])->max('order') ?? 0) + 1;

        $subcategory = Subcategory::create($data);

        return response()->json([
            'message' => 'Subcategoría creada correctamente.',
            'subcategory' => $this->serializeSubcategory($subcategory->load('dishes')),
        ], Response::HTTP_CREATED);
    }

    public function updateSubcategory(Request $request, Subcategory $subcategory)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $subcategory->update($data);

        return response()->json([
            'message' => 'Subcategoría actualizada correctamente.',
            'subcategory' => $this->serializeSubcategory($subcategory->fresh('dishes')),
        ]);
    }

    public function destroySubcategory(Subcategory $subcategory)
    {
        $subcategory->dishes()->update(['subcategory_id' => null]);
        $subcategory->delete();

        return response()->json([
            'message' => 'Subcategoría eliminada correctamente.',
        ]);
    }

    public function reorderSubcategories(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:subcategories,id'],
        ]);

        foreach ($data['order'] as $index => $subcategoryId) {
            Subcategory::where('id', $subcategoryId)
                ->where('category_id', $data['category_id'])
                ->update(['order' => $index + 1]);
        }

        return response()->json([
            'message' => 'Orden de subcategorías actualizado.',
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
        $data['position'] = $this->nextDishPosition((int) $data['category_id'], $data['subcategory_id'] ?? null);

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

        if ($dish->category_id !== (int) $data['category_id'] || $dish->subcategory_id !== ($data['subcategory_id'] ?? null)) {
            $data['position'] = $this->nextDishPosition((int) $data['category_id'], $data['subcategory_id'] ?? null);
        }

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
            'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id'],
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:dishes,id'],
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

        return response()->json([
            'message' => 'Orden actualizado.',
        ]);
    }

    protected function validateDish(Request $request, ?int $dishId = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'integer', 'exists:subcategories,id'],
            'featured_on_cover' => ['nullable', 'boolean'],
            'visible' => ['nullable', 'boolean'],
            'image' => [$request->hasFile('image') ? 'required' : 'nullable', 'image', 'max:5120'],
            'recommended_dishes' => ['nullable', 'array'],
            'recommended_dishes.*' => [
                'integer',
                'exists:dishes,id',
                $dishId ? Rule::notIn([$dishId]) : null,
            ],
            'extra_ids' => ['nullable', 'array'],
            'extra_ids.*' => ['integer', 'exists:extras,id'],
        ]);

        unset($validated['image']);

        $relations = [
            'recommended_dishes' => collect($request->input('recommended_dishes', []))
                ->filter(fn ($id) => (int) $id !== (int) $dishId)
                ->unique()
                ->values()
                ->all(),
            'extras' => collect($request->input('extra_ids', []))
                ->unique()
                ->values()
                ->all(),
        ];

        $validated['subcategory_id'] = $this->assertSubcategoryHierarchy(
            (int) $validated['category_id'],
            $request->input('subcategory_id')
        );

        return [$validated, $relations];
    }

    protected function syncRelations(Dish $dish, array $relations): void
    {
        if (array_key_exists('recommended_dishes', $relations)) {
            $dish->recommendedDishes()->sync($relations['recommended_dishes']);
        }

        if (array_key_exists('extras', $relations)) {
            $dish->extras()->sync($relations['extras']);
        }
    }

    protected function validateCategory(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'show_on_cover' => ['nullable', 'boolean'],
            'cover_title' => ['nullable', 'string', 'max:255'],
            'cover_subtitle' => ['nullable', 'string', 'max:255'],
        ]);

        $data['show_on_cover'] = $request->boolean('show_on_cover', false);
        $data['cover_title'] = filled($data['cover_title']) ? $data['cover_title'] : null;
        $data['cover_subtitle'] = filled($data['cover_subtitle']) ? $data['cover_subtitle'] : null;

        if ($data['show_on_cover'] && blank($data['cover_title'])) {
            $data['cover_title'] = $data['name'];
        }

        return $data;
    }

    protected function assertSubcategoryHierarchy(int $categoryId, $subcategoryId): ?int
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
                'subcategory_id' => 'La subcategoría seleccionada no pertenece a la categoría indicada.',
            ]);
        }

        return $id;
    }

    protected function nextDishPosition(int $categoryId, ?int $subcategoryId = null): int
    {
        $max = Dish::where('category_id', $categoryId)
            ->when($subcategoryId, fn ($query) => $query->where('subcategory_id', $subcategoryId))
            ->when(is_null($subcategoryId), fn ($query) => $query->whereNull('subcategory_id'))
            ->max('position');

        return (int) $max + 1;
    }

    protected function serializeCategory(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'order' => $category->order,
            'show_on_cover' => (bool) $category->show_on_cover,
            'cover_title' => $category->cover_title,
            'cover_subtitle' => $category->cover_subtitle,
            'subcategories' => $category->subcategories?->map(fn (Subcategory $subcategory) => $this->serializeSubcategory($subcategory))->values() ?? [],
            'dishes' => $category->dishes?->map(fn (Dish $dish) => $this->serializeDish($dish))->values() ?? [],
        ];
    }

    protected function serializeSubcategory(Subcategory $subcategory): array
    {
        return [
            'id' => $subcategory->id,
            'name' => $subcategory->name,
            'order' => $subcategory->order,
            'category_id' => $subcategory->category_id,
            'dishes' => $subcategory->dishes?->map(fn (Dish $dish) => $this->serializeDish($dish))->values() ?? [],
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

        $extras = $dish->relationLoaded('extras')
            ? $dish->extras
            : $dish->extras()->get(['extras.id', 'name', 'price', 'view_scope']);

        return [
            'id' => $dish->id,
            'name' => $dish->name,
            'description' => $dish->description,
            'price' => (float) $dish->price,
            'category_id' => $dish->category_id,
            'category_name' => $dish->category?->name,
            'subcategory_id' => $dish->subcategory_id,
            'subcategory_name' => $dish->subcategory?->name,
            'image' => $dish->image ? asset('storage/' . $dish->image) : null,
            'visible' => (bool) $dish->visible,
            'featured_on_cover' => (bool) $dish->featured_on_cover,
            'position' => $dish->position,
            'recommended_dishes' => $recommended,
            'extras' => $extras->map(fn ($extra) => [
                'id' => $extra->id,
                'name' => $extra->name,
                'price' => (float) $extra->price,
                'view_scope' => $extra->view_scope,
            ])->values(),
        ];
    }
}
