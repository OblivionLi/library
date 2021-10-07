<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'title',
        'author',
        'translator',
        'status',
        'description',
        'date_release',
        'cover',
        'rating',
        'total_reviews',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    // books that belongs to user (one-to-many | inverse)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // chapters that belongs to book (one-to-many)
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    // reviews that belongs to book (one-to-many)
    public function reviews()
    {
        return $this->hasMany(ReviewBook::class);
    }

    // genres that belongs to book (many-to-many)
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_book');
    }

    // scope func that returns relationship data
    public function scopeInfo($query)
    {
        return $query->with(['user:name', 'chapters', 'reviews', 'genres:name']);
    }
}
