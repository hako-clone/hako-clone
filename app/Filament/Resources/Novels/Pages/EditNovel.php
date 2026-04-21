<?php

namespace App\Filament\Resources\Novels\Pages;

use App\Filament\Resources\Novels\NovelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth; // 🌟 BẮT BUỘC PHẢI CÓ DÒNG NÀY

class EditNovel extends EditRecord
{
    protected static string $resource = NovelResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();

        // 🛡️ BẢO VỆ DỮ LIỆU: 
        // Nếu không phải super_admin, thì tuyệt đối không cho phép ghi đè group_id
        // Việc này đảm bảo Admin nhóm không thể "hack" để đổi truyện sang nhóm khác
        if ($user && $user->role !== 'super_admin') {
            unset($data['group_id']); 
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            // Nút xóa truyện ở góc trên bên phải
            DeleteAction::make(),
        ];
    }
}