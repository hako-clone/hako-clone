<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                // 1. Tên hiển thị
                TextInput::make('name')
                    ->required()
                    ->label('Tên hiển thị'),

                // 2. Email
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email'),

                // 3. Mật khẩu (Chỉ bắt buộc nhập khi tạo mới, khi sửa nếu để trống thì giữ nguyên pass cũ)
                TextInput::make('password')
                    ->password()
                    ->revealable() // Hiện nút con mắt để xem mật khẩu
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->label('Mật khẩu'),

                // 🌟 4. Ô CHỌN NHÓM DỊCH
                Select::make('group_id')
                    ->relationship('group', 'name')
                    ->label('Thuộc Nhóm Dịch')
                    ->preload()
                    ->searchable()
                    ->placeholder('Chọn nhóm cho thành viên này...'),

                // 🌟 5. Ô CHỌN CHỨC VỤ
                Select::make('role')
                    ->options([
                        'user' => 'Thành viên thường',
                        'admin' => 'Admin Nhóm Dịch',
                        'super_admin' => 'Trùm Cuối (Super Admin)',
                    ])
                    ->required()
                    ->default('user')
                    ->label('Chức vụ')
                    ->native(false), // Dùng giao diện menu thả xuống đẹp hơn của Filament
            ]);
    }
}