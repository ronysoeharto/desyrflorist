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
            $data['image'] = Storage::disk('public')->url($request->file('image')->store('custom-options', 'public'));
        }

        CustomBouquetOption::create($data);

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
            $data['image'] = Storage::disk('public')->url($request->file('image')->store('custom-options', 'public'));
        }

        $customOption->update($data);

        return redirect()->route('admin.custom-options.index')->with('success', 'Opsi buket berhasil diperbarui.');
    }

    public function destroy(CustomBouquetOption $customOption)
    {
        $customOption->delete();

        return redirect()->route('admin.custom-options.index')->with('success', 'Opsi buket berhasil dihapus.');
    }
}
