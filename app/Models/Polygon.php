<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polygon extends Model
{
    protected $fillable = ['name', 'coordinates', 'user_id'];

    




    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
