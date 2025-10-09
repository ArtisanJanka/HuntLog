<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_leader',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function waypoints()
    {
        return $this->hasMany(Waypoint::class);
    }

    public function polygons()
    {
        return $this->hasMany(Polygon::class);
        return $this->belongsToMany(\App\Models\Group::class);

    }

    public function team()
    {
        return $this->hasMany(User::class, 'leader_id');
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function groups()
    {
        return $this->belongsToMany(\App\Models\Group::class)
        ->withPivot(['role','status'])->withTimestamps();

    }
    public function ledGroups()
    {
        return $this->hasMany(\App\Models\Group::class, 'leader_id');
    }

}