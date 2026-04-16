<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Nhớ thêm dòng này để dùng chức năng HasMany
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // Khai báo mối quan hệ: Truyện này có nhiều Tập
    public function volumes(): HasMany
    {
        return $this->hasMany(Volume::class);
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}