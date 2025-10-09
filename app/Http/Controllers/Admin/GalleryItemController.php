<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\HuntingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryItemController extends Controller
{

    public function index()
    {
        $galleryItems = GalleryItem::with('huntingType')->latest()->get();
        return view('admin.gallery.index', compact('galleryItems'));
    }

    public function create()
    {
        $types = HuntingType::all();
        return view('admin.gallery.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hunting_type_id' => 'required|exists:hunting_types,id',
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'link'  => 'nullable|url',
        ]);

        $path = $request->file('image')->store('gallery', 'public');

        GalleryItem::create([
            'title'          => $data['title'],
            'image_path'     => $path,
            'hunting_type_id'=> $data['hunting_type_id'],
            'link'           => $data['link'] ?? null,
        ]);

        return redirect()->route('admin.gallery.index')
                         ->with('success', 'Gallery item created successfully!');
    }

    public function edit(GalleryItem $gallery)
    {
        $types = HuntingType::all();
        return view('admin.gallery.edit', [
            'item'  => $gallery,
            'types' => $types,
        ]);
    }

    public function update(Request $request, GalleryItem $gallery)
    {
        $data = $request->validate([
            'hunting_type_id' => 'required|exists:hunting_types,id',
            'title'           => 'nullable|string|max:255',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'link'            => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {

            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')
                         ->with('success', 'Gallery item updated successfully!');
    }


    public function destroy(GalleryItem $gallery)
    {
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')
                         ->with('success', 'Gallery item deleted successfully!');
    }
}
