<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HuntingType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function galleryItems()
    {
        return $this->hasMany(GalleryItem::class);
    }
}
