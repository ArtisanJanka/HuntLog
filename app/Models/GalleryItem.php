<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
    'title',
    'image_path',
    'hunting_type_id',
    'link',
    ];


    /**
     * Each gallery item belongs to a hunting type.
     */
    public function huntingType()
    {
        return $this->belongsTo(HuntingType::class);
    }

    /**
     * Return full URL for the image (for <img src>).
     */
    public function url()
    {
    return asset('storage/' . $this->image_path);
    }


    /**
     * Optional: a link related to this gallery item.
     */
    public function joinUrl(): string
    {
        return route('join-group.store', ['group_name' => $this->huntingType->name]);
    }
}
