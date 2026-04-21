<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên hiển thị')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('group.name') 
                    ->label('Thuộc Nhóm')
                    ->badge() 
                    ->color('info')
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Chức vụ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',   
                        'admin'       => 'warning',  
                        default       => 'gray',     
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'super_admin' => 'Trùm Cuối',
                        'admin'       => 'Admin Nhóm',
                        default       => 'Thành viên',
                    }),
            ])
            ->filters([
                // Nơi thêm các bộ lọc
            ]);
            // 🌟 Đã xóa sạch block actions() và bulkActions() gây lỗi
    }
}