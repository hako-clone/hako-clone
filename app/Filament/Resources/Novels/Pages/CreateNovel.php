<?php

namespace App\Filament\Resources\Novels\Pages;

use App\Filament\Resources\Novels\NovelResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateNovel extends CreateRecord
{
    protected static string $resource = NovelResource::class;

    // 🌟 LOGIC TỰ ĐỘNG GÁN NHÓM TRƯỚC KHI LƯU
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        // Nếu người đăng là Admin nhóm (có group_id), tự động lấy group_id của họ gán vào truyện
        if ($user->role === 'admin' && $user->group_id) {
            $data['group_id'] = $user->group_id;
        }

        // Nếu là Trùm Cuối (super_admin), hệ thống sẽ lấy theo cái nhóm mà bạn đã chọn ở ô Select trong Form
        
        return $data;
    }
}