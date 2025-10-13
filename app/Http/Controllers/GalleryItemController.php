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
        $huntingTypes = HuntingType::all();
        return view('admin.gallery.create', compact('huntingTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hunting_type_id' => 'required|exists:hunting_types,id',
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'link' => 'nullable|url',
        ]);

        $data['image_path'] = $request->file('image')->store('gallery', 'public');

        GalleryItem::create($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Veiksmīgi izveidots!');
    }

    public function edit(GalleryItem $galleryItem)
    {
        $huntingTypes = HuntingType::all();
        return view('admin.gallery.edit', compact('galleryItem', 'huntingTypes'));
    }

    public function update(Request $request, GalleryItem $galleryItem)
    {
        $data = $request->validate([
            'hunting_type_id' => 'required|exists:hunting_types,id',
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'link' => 'nullable|url',
        ]);

        if ($request->hasFile('image')) {
            if ($galleryItem->image_path && Storage::disk('public')->exists($galleryItem->image_path)) {
                Storage::disk('public')->delete($galleryItem->image_path);
            }
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $galleryItem->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Veiksmīgi atjaunināts!');
    }


    public function destroy(GalleryItem $galleryItem)
    {
        if ($galleryItem->image_path && Storage::disk('public')->exists($galleryItem->image_path)) {
            Storage::disk('public')->delete($galleryItem->image_path);
        }

        $galleryItem->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Veiksmīgi izdzēsts!');
    }
}
