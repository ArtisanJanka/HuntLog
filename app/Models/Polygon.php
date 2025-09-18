<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polygon extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'coordinates'];

    protected $casts = [
        'coordinates' => 'array', // automatically cast JSON to array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
