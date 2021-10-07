<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory, Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'book_id',
        'user_id',
        'title',
        'content',
        'date_release',
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

    // chapters that belongs to user (one-to-many | inverse)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // chapters that belongs to book (one-to-many | inverse)
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // reviews that belongs to chapter (one-to-many)
    public function reviews()
    {
        return $this->hasMany(ReviewChapter::class);
    }

    // scope func that returns relationship data
    public function scopeInfo($query)
    {
        return $query->with(['user:name', 'book', 'reviews']);
    }
}
