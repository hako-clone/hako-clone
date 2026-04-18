<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Novel extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author',
        'illustrator',
        'status',
        'cover_image',
        'description',
    ];

    public function volumes(): HasMany
    {
        return $this->hasMany(Volume::class);
    }

    public function chapters(): HasManyThrough
    {
        return $this->hasManyThrough(Chapter::class, Volume::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    // 🌟 1 Truyện có thể có nhiều User theo dõi
    public function followers()
    {
        return $this->belongsToMany(User::class);
    }
}