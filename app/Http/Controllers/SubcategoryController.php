<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with([
            'subcategories' => fn ($query) => $query->orderBy('order')->withCount('dishes'),
        ])->orderBy('order')->get();

        return view('subcategories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('order')->get();

        return view('subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'background_color' => ['nullable', 'string', 'max:32'],
            'text_color' => ['nullable', 'string', 'max:32'],
        ]);

        $data['order'] = (Subcategory::where('category_id', $data['category_id'])->max('order') ?? 0) + 1;

        Subcategory::create($data);

        return $this->redirectResponse($request, 'Subcategoría creada correctamente.');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::orderBy('order')->get();

        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'background_color' => ['nullable', 'string', 'max:32'],
            'text_color' => ['nullable', 'string', 'max:32'],
        ]);

        $categoryChanged = $subcategory->category_id !== (int) $data['category_id'];

        if ($categoryChanged) {
            $data['order'] = (Subcategory::where('category_id', $data['category_id'])->max('order') ?? 0) + 1;
        }

        $subcategory->update($data);

        return $this->redirectResponse($request, 'Subcategoría actualizada.');
    }

    public function destroy(Request $request, Subcategory $subcategory)
    {
        $subcategory->dishes()->update(['subcategory_id' => null]);
        $subcategory->delete();

        return $this->redirectResponse($request, 'Subcategoría eliminada.');
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:subcategories,id'],
        ]);

        foreach ($data['order'] as $index => $id) {
            Subcategory::where('id', $id)
                ->where('category_id', $data['category_id'])
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    protected function redirectResponse(Request $request, string $message)
    {
        $redirectTo = $request->input('redirect_to');

        if ($redirectTo) {
            return redirect()->to($redirectTo)->with('success', $message);
        }

        return redirect()->route('admin.new-panel', [
            'section' => 'menu-section',
            'open' => 'menu-create',
            'expand' => 'dish-categories',
        ])->with('success', $message);
    }
}
