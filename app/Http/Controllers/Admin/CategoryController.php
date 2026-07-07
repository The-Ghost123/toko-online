<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:categories',
            'deskripsi'     => 'nullable|string',
            'foto_kategori' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only('nama_kategori', 'deskripsi');

        if ($request->hasFile('foto_kategori')) {
            $data['foto_kategori'] = $request->file('foto_kategori')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:categories,nama_kategori,' . $category->id,
            'deskripsi'     => 'nullable|string',
            'foto_kategori' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only('nama_kategori', 'deskripsi');

        if ($request->hasFile('foto_kategori')) {
            if ($category->foto_kategori && Storage::disk('public')->exists($category->foto_kategori)) {
                Storage::disk('public')->delete($category->foto_kategori);
            }
            $data['foto_kategori'] = $request->file('foto_kategori')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        if ($category->foto_kategori && Storage::disk('public')->exists($category->foto_kategori)) {
            Storage::disk('public')->delete($category->foto_kategori);
        }

        $category->delete();
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Kategori berhasil dihapus!');
    }
}