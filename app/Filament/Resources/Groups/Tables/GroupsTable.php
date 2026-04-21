<?php

namespace App\Filament\Resources\Groups\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class GroupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Cột 1: Tên Nhóm
                TextColumn::make('name')
                    ->label('Tên Nhóm Dịch')
                    ->searchable()
                    ->sortable(),

                // Cột 2: Đếm số lượng thành viên trong nhóm
                TextColumn::make('users_count')
                    ->counts('users') // Tự động đếm số user liên kết với nhóm này
                    ->label('Số thành viên')
                    ->badge()
                    ->color('success')
                    ->sortable(),
            ])
            ->filters([
                // Nơi thêm các bộ lọc nếu cần
            ]);
            // 🌟 Đã xóa sạch block actions() và bulkActions() để chống lỗi 500
    }
}