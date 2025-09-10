<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\HuntingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        // Fetch all gallery items with their hunting type
        $galleryItems = GalleryItem::with('huntingType')->latest()->get();

        // Pass to the view
        return view('admin.gallery.index', compact('galleryItems'));
    }

    public function create()
    {
        $huntingTypes = HuntingType::all();
        return view('admin.gallery.create', compact('huntingTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'hunting_type_id' => 'required|exists:hunting_types,id',
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('gallery', 'public');
        $data['image_path'] = $path;

        GalleryItem::create($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item created!');
    }

    public function edit(GalleryItem $galleryItem)
    {
        $huntingTypes = HuntingType::all();
        return view('admin.gallery.edit', compact('galleryItem', 'huntingTypes'));
    }

    public function update(Request $request, GalleryItem $galleryItem)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'hunting_type_id' => 'required|exists:hunting_types,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($galleryItem->image_path) {
                Storage::disk('public')->delete($galleryItem->image_path);
            }
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $galleryItem->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item updated!');
    }

    public function destroy(GalleryItem $galleryItem)
    {
        if ($galleryItem->image_path) {
            Storage::disk('public')->delete($galleryItem->image_path);
        }

        $galleryItem->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery item deleted!');
    }
}
