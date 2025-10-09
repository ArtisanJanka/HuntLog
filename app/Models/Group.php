<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['hunting_type_id','leader_id','name','slug','description'];

    public function huntingType(){ return $this->belongsTo(HuntingType::class); }
    public function leader(){ return $this->belongsTo(User::class, 'leader_id'); }
    public function members(){
        return $this->belongsToMany(User::class)->withPivot(['role','status'])->withTimestamps();
    }
}

