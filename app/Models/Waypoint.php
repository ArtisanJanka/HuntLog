<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waypoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'latitude',
        'longitude',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
