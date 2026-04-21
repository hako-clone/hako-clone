<?php

namespace App\Filament\Resources\Novels\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NovelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 1. Tiêu đề truyện
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Tên truyện')
                    ->live(onBlur: true) // Lắng nghe khi người dùng gõ xong tên truyện
                    ->afterStateUpdated(fn ($operation, $state, $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                // 2. Đường dẫn (Slug) - Tự động sinh ra từ Tên truyện
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->label('Đường dẫn (Slug)'),

                // 3. Tác giả
                TextInput::make('author')
                    ->label('Tác giả')
                    ->maxLength(255),

                // 4. Họa sĩ
                TextInput::make('illustrator')
                    ->label('Họa sĩ')
                    ->maxLength(255),

                // 5. Trạng thái
                Select::make('status')
                    ->options([
                        'ongoing' => 'Đang tiến hành',
                        'completed' => 'Đã hoàn thành',
                        'paused' => 'Tạm ngưng',
                    ])
                    ->default('ongoing')
                    ->required()
                    ->label('Trạng thái'),

                // 6. Ảnh bìa
                FileUpload::make('cover_image')
                    ->image()
                    ->directory('novels/covers') // Tự động lưu ảnh vào thư mục storage/app/public/novels/covers
                    ->label('Ảnh bìa'),

                // 🌟 7. Ô CHỌN NHÓM (Dành riêng cho Trùm Cuối)
                Select::make('group_id')
                    ->relationship('group', 'name')
                    ->label('Phân Nhóm Quản Lý (Super Admin)')
                    ->preload()
                    ->searchable()
                    ->placeholder('Chọn nhóm quản lý truyện này...')
                    ->visible(false)
                    ->visible(fn () => Auth::user()?->role === 'super_admin'),

                // 8. Tóm tắt truyện (Cho full chiều ngang để dễ soạn thảo)
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->label('Tóm tắt truyện'),
            ]);
    }
}