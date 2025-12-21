<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Carga las categorías y sus platos relacionados
        $categories = Category::with('dishes')->orderBy('order')->get();
        return view('categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $category = new Category;
        $category->name = $request->name;
        $category->order = (Category::max('order') ?? 0) + 1;
        $category->save();

        return redirect()->route('admin.new-panel', ['section' => 'menu-section', 'open' => 'menu-config'])->with('success', 'Categoría creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $category->name = $request->name;
        $category->save();

        return redirect()->route('admin.new-panel', ['section' => 'menu-section', 'open' => 'menu-config'])->with('success', 'Categoría actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.new-panel', ['section' => 'menu-section', 'open' => 'menu-config'])->with('success', 'Categoría eliminada con éxito.');
    }

    public function updateOrder(Request $request)
    {
        $data = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:categories,id',
        ]);

        foreach ($data['order'] as $index => $id) {
            Category::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function getCategoriesJson()
    {
        $categories = Category::all(); // Get all categories from your database
        return response()->json($categories); // Return the categories as a JSON response
    }
}
