<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name'
    ];

    // books that belongs to genre (many-to-many)
    public function books()
    {
        return $this->belongsToMany(Book::class, 'genre_book');
    }

    // scope func that returns relationship data
    public function scopeInfo($query)
    {
        return $query->with(['books']);
    }
}
