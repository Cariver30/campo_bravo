<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $data['order'] = (Subcategory::where('category_id', $data['category_id'])->max('order') ?? 0) + 1;

        Subcategory::create($data);

        return redirect()->route('admin.new-panel', [
            'section' => 'menu-section',
            'open' => 'menu-create',
            'expand' => 'dish-categories',
        ])->with('success', 'Subcategoría creada correctamente.');
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $subcategory->update($data);

        return redirect()->route('admin.new-panel', [
            'section' => 'menu-section',
            'open' => 'menu-create',
            'expand' => 'dish-categories',
        ])->with('success', 'Subcategoría actualizada.');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->dishes()->update(['subcategory_id' => null]);
        $subcategory->delete();

        return redirect()->route('admin.new-panel', [
            'section' => 'menu-section',
            'open' => 'menu-create',
            'expand' => 'dish-categories',
        ])->with('success', 'Subcategoría eliminada.');
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
}
