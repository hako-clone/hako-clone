<?php
namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // 🌟 Thêm dòng này
use Filament\Panel; // 🌟 Thêm dòng này
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 🌟 Thêm chữ "implements FilamentUser" vào sau class
class User extends Authenticatable implements FilamentUser 
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Nhớ khai báo thêm 2 cột này để lưu được dữ liệu
        'phone',
        'group_id',

    ];

    // ... các code cũ giữ nguyên ...

    // 🌟 THÊM HÀM NÀY ĐỂ BẢO VỆ TRANG ADMIN
    public function canAccessPanel(Panel $panel): bool
    {
        // Chỉ những ai có role là 'admin' mới được vào trang quản trị
        return $this->role === 'admin' || $this->role === 'super_admin';
    }
    // 🌟 1 User có thể theo dõi nhiều Truyện
    public function followedNovels()
    {
        return $this->belongsToMany(Novel::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    
}