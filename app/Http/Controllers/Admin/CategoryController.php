<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort_order')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image_file' => 'nullable|image|max:10240',
            'image_url' => 'nullable|url|max:500',
            'image' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = $validated['image_url'] ?? $validated['image'] ?? null;

        if ($request->hasFile('image_file')) {
            try {
                $imagePath = ImageUploadService::storeImage($request->file('image_file'), 'categories');
            } catch (\Throwable $e) {
                \Log::error('Upload category image failed: '.$e->getMessage());

                return back()->withInput()->withErrors(['image_file' => 'Gagal mengunggah foto kategori: '.$e->getMessage()]);
            }
        }

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        try {
            Category::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'image' => $imagePath,
                'sort_order' => $validated['sort_order'] ?? 0,
                'status' => $validated['status'] === 'active',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Create category failed: '.$e->getMessage());

            return back()->withInput()->withErrors(['name' => 'Gagal menyimpan kategori: '.$e->getMessage()]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $category = Category::withCount('products')->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json([
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $category->image,
                'image_url' => $category->image_url,
                'sort_order' => $category->sort_order,
                'status' => $category->status ? 'active' : 'inactive',
            ]);
        }

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'image_file' => 'nullable|image|max:10240',
            'image_url' => 'nullable|url|max:500',
            'image' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = $category->image;

        if ($request->hasFile('image_file')) {
            // Delete old file from public disk if local path
            if (! empty($category->image) && ! str_starts_with($category->image, 'http://') && ! str_starts_with($category->image, 'https://')) {
                try {
                    Storage::disk('public')->delete($category->image);
                } catch (\Throwable $e) {
                    // Ignore deletion error
                }
            }

            try {
                $imagePath = ImageUploadService::storeImage($request->file('image_file'), 'categories');
            } catch (\Throwable $e) {
                \Log::error('Update category image failed: '.$e->getMessage());

                return back()->withInput()->withErrors(['image_file' => 'Gagal mengunggah foto baru: '.$e->getMessage()]);
            }
        } elseif (! empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        } elseif (! empty($validated['image'])) {
            $imagePath = $validated['image'];
        }

        // Generate slug if name changed
        $slug = $category->slug;
        if ($category->name !== $validated['name']) {
            $baseSlug = Str::slug($validated['name']);
            $slug = $baseSlug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $baseSlug.'-'.$counter;
                $counter++;
            }
        }

        try {
            $category->update([
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'] ?? null,
                'image' => $imagePath,
                'sort_order' => $validated['sort_order'] ?? 0,
                'status' => $validated['status'] === 'active',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Update category failed: '.$e->getMessage());

            return back()->withInput()->withErrors(['name' => 'Gagal memperbarui kategori: '.$e->getMessage()]);
        }

        return redirect()->route('admin.categories.index')->with('success', "Kategori '{$category->name}' berhasil diperbarui.");
    }

    public function destroy(int $id)
    {
        $category = Category::withCount('products')->findOrFail($id);

        if ($category->products_count > 0) {
            return back()->withErrors(['category' => "Kategori '{$category->name}' tidak dapat dihapus karena masih memiliki {$category->products_count} produk terkait."]);
        }

        if (! empty($category->image) && ! str_starts_with($category->image, 'http://') && ! str_starts_with($category->image, 'https://')) {
            try {
                Storage::disk('public')->delete($category->image);
            } catch (\Throwable $e) {
                // Ignore deletion error
            }
        }

        $category->delete();

        return back()->with('success', "Kategori '{$category->name}' berhasil dihapus.");
    }
}
