<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HuntingType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected static function booted()
    {
        static::creating(function ($huntingType) {
            $huntingType->slug = Str::slug($huntingType->name);
        });
    }

    public function galleryItems()
    {
        return $this->hasMany(GalleryItem::class);
    }
}
