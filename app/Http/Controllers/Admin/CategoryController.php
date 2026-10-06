<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $categories = $query->orderBy('sort_order', 'asc')
                            ->orderBy('name', 'asc')
                            ->get();

        $stats = [
            'total'      => Category::count(),
            'active'     => Category::where('status', 'active')->count(),
            'inactive'   => Category::where('status', 'inactive')->count(),
            'products'   => Product::whereNotNull('category')->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        // Ensure slug uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        Category::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'status'      => $request->status,
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Category "' . $request->name . '" created successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'status'      => 'required|in:active,inactive',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $newSlug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->name);

        $oldSlug = $category->slug;

        // If slug changed, cascade update existing products mapped to old slug
        if ($oldSlug !== $newSlug) {
            Product::where('category', $oldSlug)->update(['category' => $newSlug]);
        }

        $category->update([
            'name'        => $request->name,
            'slug'        => $newSlug,
            'description' => $request->description,
            'status'      => $request->status,
            'sort_order'  => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Category "' . $category->name . '" updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        $productsCount = Product::where('category', $category->slug)->count();

        if ($productsCount > 0) {
            return back()->with('error', "Cannot delete category '{$category->name}' because it contains {$productsCount} products. Please reassign the products first or mark this category as inactive.");
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', "Category '{$categoryName}' deleted successfully.");
    }

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $newStatus = $category->status === 'active' ? 'inactive' : 'active';
        $category->update(['status' => $newStatus]);

        return back()->with('success', "Category '{$category->name}' is now {$newStatus}.");
    }
}
