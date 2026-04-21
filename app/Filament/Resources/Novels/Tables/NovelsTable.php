<?php

namespace App\Filament\Resources\Novels\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Auth; // Thêm thư viện Auth để kiểm tra quyền

class NovelsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Ảnh bìa
                ImageColumn::make('cover_image')
                    ->label('Ảnh bìa')
                    ->square(), // Hiển thị ảnh vuông cho đẹp

                // 2. Tên truyện
                TextColumn::make('title')
                    ->label('Tên truyện')
                    ->searchable()
                    ->sortable()
                    ->wrap(), // Xuống dòng nếu tên quá dài

                // 3. Tác giả
                TextColumn::make('author')
                    ->label('Tác giả')
                    ->searchable(),

                // 🌟 4. Cột Nhóm Dịch (Đã gài lệnh: Chỉ Trùm Cuối mới thấy)
                TextColumn::make('group.name')
                    ->label('Nhóm Dịch')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->placeholder('Chưa phân nhóm')
                    ->visible(fn () => Auth::check() && Auth::user()->role === 'super_admin'),

                // 5. Trạng thái
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ongoing' => 'success',
                        'completed' => 'warning',
                        'paused' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'ongoing' => 'Đang tiến hành',
                        'completed' => 'Đã hoàn thành',
                        'paused' => 'Tạm ngưng',
                        default => $state,
                    }),
            ])
            ->filters([
                // Nơi thêm bộ lọc
            ]);
            // 🌟 Đã ẩn phần Actions đi vì bạn chỉ cần click thẳng vào hàng để Edit (Vừa nhanh vừa chống lỗi 500)
    }
}