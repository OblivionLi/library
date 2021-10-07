<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'is_admin'
    ];

    // users that belongs to role (many-to-many)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    // permissions that belongs to role (many-to-many)
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    // scope func that returns relationship data
    public function scopeInfo($query)
    {
        return $query->with(['users:name', 'permissions:name']);
    }
}
