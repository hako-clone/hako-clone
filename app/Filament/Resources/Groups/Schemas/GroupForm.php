<?php

namespace App\Filament\Resources\Groups\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class GroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. Ô nhập Tên Nhóm
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Tên Nhóm Dịch')
                    ->placeholder('Ví dụ: Nhóm Cú Đêm...'),

                // 2. Ô chọn Thành viên (Có thể chọn nhiều người cùng lúc)
                Select::make('users')
                    ->relationship('users', 'name') // Liên kết với hàm users() trong model Group
                    ->multiple() // Cho phép chọn nhiều người
                    ->preload() // Tải sẵn danh sách để tìm kiếm nhanh
                    ->searchable()
                    ->label('Thêm Thành viên')
                    ->placeholder('Chọn các thành viên cho nhóm này...'),
            ]);
    }
}