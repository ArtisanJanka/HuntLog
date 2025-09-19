<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hunting_type_id',
        'group_name',
        'status',
    ];

    // Relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship to HuntingType
    public function huntingType()
    {
        return $this->belongsTo(HuntingType::class);
    }
}
