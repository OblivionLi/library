<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // roles that belongs to user (many-to-many)
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    // book reviews that belongs to user (one-to-many)
    public function bookReviews()
    {
        return $this->hasMany(ReviewBook::class);
    }

    // chapter reviews that belongs to user (one-to-many)
    public function chapterReviews()
    {
        return $this->hasMany(ReviewChapter::class);
    }

    // chapters that belongs to user (one-to-many)
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    // books that belongs to user (one-to-many)
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // scope func that returns relationship data
    public function scopeInfo($query)
    {
        return $query->with(['roles:name', 'bookReviews', 'chapterReviews', 'chapters', 'books']);
    }
}
