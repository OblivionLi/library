<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    // roles that belongs to permission (many-to-many)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }

    // scope func that returns relationship data
    public function scopeInfo($query)
    {
        return $query->with(['roles:name']);
    }
}
