<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('events')
            ->orderBy('name_en')
            ->get()
            ->map(fn($category) => [
                'id' => $category->id,
                'name' => $category->translated_name,
                'slug' => $category->slug,
                'description' => $category->translated_description,
                'color' => $category->color,
                'icon' => $category->icon,
                'events_count' => $category->events_count,
                'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                // Original translations for editing
                'name_en' => $category->name_en,
                'name_fr' => $category->name_fr,
                'description_en' => $category->description_en,
                'description_fr' => $category->description_fr,
            ]);

        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:100',
            'name_fr' => 'required|string|max:100',
            'description_en' => 'nullable|string',
            'description_fr' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);

        // Keep old columns for compatibility
        $validated['name'] = $validated['name_en'];
        $validated['description'] = $validated['description_en'];
        $validated['slug'] = Str::slug($validated['name_en']);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:100',
            'name_fr' => 'required|string|max:100',
            'description_en' => 'nullable|string',
            'description_fr' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);

        // Keep old columns for compatibility
        $validated['name'] = $validated['name_en'];
        $validated['description'] = $validated['description_en'];
        $validated['slug'] = Str::slug($validated['name_en']);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Check if category has events
        if ($category->events()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated events.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
