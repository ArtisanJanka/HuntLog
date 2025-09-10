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
        $items = GalleryItem::with('huntingType')->latest()->paginate(20);
        return view('admin.gallery.index', compact('items'));
    }

    public function create()
    {
        $types = HuntingType::orderBy('name')->get();
        return view('admin.gallery.create', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hunting_type_id' => ['required','exists:hunting_types,id'],
            'title' => ['nullable','string','max:255'],
            'image' => ['required','image','max:4096'], // 4MB
        ]);

        $path = $request->file('image')->store('gallery', 'public');

        $item = GalleryItem::create([
            'hunting_type_id' => $data['hunting_type_id'],
            'title' => $data['title'] ?? null,
            'image_path' => $path,
        ]);

        return redirect()->route('admin.gallery.index')->with('status','Image created.');
    }

    public function edit(GalleryItem $gallery)
    {
        $types = HuntingType::orderBy('name')->get();
        return view('admin.gallery.edit', ['item' => $gallery, 'types' => $types]);
    }

    public function update(Request $request, GalleryItem $gallery)
    {
        $data = $request->validate([
            'hunting_type_id' => ['required','exists:hunting_types,id'],
            'title' => ['nullable','string','max:255'],
            'image' => ['nullable','image','max:4096'],
        ]);

        $update = [
            'hunting_type_id' => $data['hunting_type_id'],
            'title' => $data['title'] ?? null,
        ];

        if ($request->hasFile('image')) {
            // delete old
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $update['image_path'] = $request->file('image')->store('gallery','public');
        }

        $gallery->update($update);

        return redirect()->route('admin.gallery.index')->with('status','Image updated.');
    }

    public function destroy(GalleryItem $gallery)
    {
        if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        $gallery->delete();
        return back()->with('status','Image deleted.');
    }
}

?>