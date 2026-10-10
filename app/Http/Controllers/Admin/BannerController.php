<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:200',
            'subtitle' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:1000',
            'image_file' => 'nullable|image|max:10240',
            'image_url' => 'nullable|url|max:500',
            'link_url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'secondary_button_text' => 'nullable|string|max:50',
            'secondary_button_url' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'badge_author' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        if (! $request->hasFile('image_file') && empty($validated['image_url'])) {
            return back()->withInput()->withErrors(['image_file' => 'Harap unggah gambar atau masukkan URL gambar banner.']);
        }

        $imagePath = $validated['image_url'] ?? null;

        if ($request->hasFile('image_file')) {
            try {
                $imagePath = ImageUploadService::storeImage($request->file('image_file'), 'banners');
            } catch (\Throwable $e) {
                \Log::error('Upload banner failed: '.$e->getMessage());

                return back()->withInput()->withErrors(['image_file' => 'Gagal mengunggah foto banner: '.$e->getMessage()]);
            }
        }

        try {
            Banner::create([
                'title' => $validated['title'] ?? null,
                'subtitle' => $validated['subtitle'] ?? null,
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
                'link_url' => $validated['link_url'] ?? null,
                'button_text' => $validated['button_text'] ?? null,
                'secondary_button_text' => $validated['secondary_button_text'] ?? null,
                'secondary_button_url' => $validated['secondary_button_url'] ?? null,
                'badge_text' => $validated['badge_text'] ?? null,
                'badge_author' => $validated['badge_author'] ?? null,
                'sort_order' => $validated['sort_order'] ?? 0,
                'status' => $validated['status'],
            ]);
        } catch (\Throwable $e) {
            \Log::error('Create banner failed: '.$e->getMessage());

            return back()->withInput()->withErrors(['title' => 'Gagal menyimpan data banner ke database: '.$e->getMessage()]);
        }

        return redirect()->route('admin.banners.index')->with('success', 'Banner baru berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $banner = Banner::findOrFail($id);

        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, int $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:200',
            'subtitle' => 'nullable|string|max:150',
            'description' => 'nullable|string|max:1000',
            'image_file' => 'nullable|image|max:10240',
            'image_url' => 'nullable|url|max:500',
            'link_url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'secondary_button_text' => 'nullable|string|max:50',
            'secondary_button_url' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'badge_author' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = $banner->image_path;

        if ($request->hasFile('image_file')) {
            // Delete old local file if stored in public disk
            if (! empty($banner->image_path) && ! str_starts_with($banner->image_path, 'http://') && ! str_starts_with($banner->image_path, 'https://')) {
                try {
                    Storage::disk('public')->delete($banner->image_path);
                } catch (\Throwable $e) {
                    // Ignore delete failure
                }
            }
            try {
                $imagePath = ImageUploadService::storeImage($request->file('image_file'), 'banners');
            } catch (\Throwable $e) {
                \Log::error('Update banner image failed: '.$e->getMessage());

                return back()->withInput()->withErrors(['image_file' => 'Gagal mengunggah foto banner baru: '.$e->getMessage()]);
            }
        } elseif (! empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        try {
            $banner->update([
                'title' => $validated['title'] ?? null,
                'subtitle' => $validated['subtitle'] ?? null,
                'description' => $validated['description'] ?? null,
                'image_path' => $imagePath,
                'link_url' => $validated['link_url'] ?? null,
                'button_text' => $validated['button_text'] ?? null,
                'secondary_button_text' => $validated['secondary_button_text'] ?? null,
                'secondary_button_url' => $validated['secondary_button_url'] ?? null,
                'badge_text' => $validated['badge_text'] ?? null,
                'badge_author' => $validated['badge_author'] ?? null,
                'sort_order' => $validated['sort_order'] ?? 0,
                'status' => $validated['status'],
            ]);
        } catch (\Throwable $e) {
            \Log::error('Update banner failed: '.$e->getMessage());

            return back()->withInput()->withErrors(['title' => 'Gagal memperbarui data banner: '.$e->getMessage()]);
        }

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $banner = Banner::findOrFail($id);

        if (! empty($banner->image_path) && ! str_starts_with($banner->image_path, 'http://') && ! str_starts_with($banner->image_path, 'https://')) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil dihapus.');
    }

    public function toggleStatus(int $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->status = ($banner->status === 'active') ? 'inactive' : 'active';
        $banner->save();

        $statusText = $banner->status === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Banner '{$banner->title}' berhasil {$statusText}.");
    }
}
