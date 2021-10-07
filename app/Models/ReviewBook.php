<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewBook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'book_id',
        'user_id',
        'rating',
        'user_name',
        'user_comment',
        'admin_name',
        'admin_comment'
    ];

    // reviews that belongs to book (one-to-many | inverse)
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // reviews that belongs to user (one-to-many | inverse)
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
