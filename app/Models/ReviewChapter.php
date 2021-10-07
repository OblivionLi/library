<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewChapter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'chapter_id',
        'user_id',
        'rating',
        'user_name',
        'user_comment',
        'admin_name',
        'admin_comment'
    ];

    // reviews that belongs to chapter (one-to-many | inverse)
    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    // reviews that belongs to user (one-to-many | inverse)
    public function user()
    {
        return $this->hasMany(User::class);
    }
}
