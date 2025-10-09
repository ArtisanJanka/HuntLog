<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'polygon_id',
        'title',
        'description',
        'start_at',
        'end_at',
        'meetup_location',
        'created_by',
        'visibility',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function polygon()
    {
        return $this->belongsTo(Polygon::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
