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
        'group_id',    
        'group_name',
        'status',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function huntingType()
    {
        return $this->belongsTo(HuntingType::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
