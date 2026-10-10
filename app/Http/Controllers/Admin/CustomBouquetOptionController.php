<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomBouquetOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomBouquetOptionController extends Controller
{
    public function index()
    {
        $options = CustomBouquetOption::orderBy('type')->orderBy('sort_order')->get();

        return view('admin.custom-options.index', compact('options'));
    }

    public function create()
    {
        return view('admin.custom-options.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:flower,wrapping,ribbon,addon',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'status' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('image');
        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            try {
                $storedPath = $request->file('image')->store('custom-options', 'public');
                $data['image'] = Storage::disk('public')->url($storedPath);
            } catch (\Throwable $e) {
                \Log::error('Upload custom bouquet option image failed: '.$e->getMessage());

                return back()->withInput()->withErrors(['image' => 'Gagal mengunggah foto opsi: '.$e->getMessage()]);
            }
        }

        try {
            CustomBouquetOption::create($data);
        } catch (\Throwable $e) {
            \Log::error('Create custom bouquet option failed: '.$e->getMessage());

            return back()->withInput()->withErrors(['name' => 'Gagal menyimpan data opsi: '.$e->getMessage()]);
        }

        return redirect()->route('admin.custom-options.index')->with('success', 'Opsi buket berhasil ditambahkan.');
    }

    public function edit(CustomBouquetOption $customOption)
    {
        return view('admin.custom-options.edit', compact('customOption'));
    }

    public function update(Request $request, CustomBouquetOption $customOption)
    {
        $request->validate([
            'type' => 'required|string|in:flower,wrapping,ribbon,addon',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'status' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('image');
        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            try {
                $storedPath = $request->file('image')->store('custom-options', 'public');
                $data['image'] = Storage::disk('public')->url($storedPath);
            } catch (\Throwable $e) {
                \Log::error('Update custom bouquet option image failed: '.$e->getMessage());

                return back()->withInput()->withErrors(['image' => 'Gagal mengunggah foto opsi baru: '.$e->getMessage()]);
            }
        }

        try {
            $customOption->update($data);
        } catch (\Throwable $e) {
            \Log::error('Update custom bouquet option failed: '.$e->getMessage());

            return back()->withInput()->withErrors(['name' => 'Gagal memperbarui data opsi: '.$e->getMessage()]);
        }

        return redirect()->route('admin.custom-options.index')->with('success', 'Opsi buket berhasil diperbarui.');
    }

    public function destroy(CustomBouquetOption $customOption)
    {
        $customOption->delete();

        return redirect()->route('admin.custom-options.index')->with('success', 'Opsi buket berhasil dihapus.');
    }
}
