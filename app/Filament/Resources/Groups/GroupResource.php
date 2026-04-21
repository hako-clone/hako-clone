<?php

namespace App\Filament\Resources\Groups;

use App\Filament\Resources\Groups\Pages\CreateGroup;
use App\Filament\Resources\Groups\Pages\EditGroup;
use App\Filament\Resources\Groups\Pages\ListGroups;
use App\Filament\Resources\Groups\Schemas\GroupForm;
use App\Filament\Resources\Groups\Tables\GroupsTable;
use App\Models\Group;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;
// Thiết lập Icon hiển thị trên thanh Menu bên trái (Sidebar)
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
// Khi tìm kiếm toàn cục (Global Search), kết quả sẽ hiển thị theo cột 'name' của bảng Group
    protected static ?string $recordTitleAttribute = 'name';

    // 🌟 CHỈ TRÙM CUỐI MỚI THẤY MENU NÀY
    public static function shouldRegisterNavigation(): bool
    {
        return \Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->role === 'super_admin';
    }


    public static function form(Schema $schema): Schema
    {
        return GroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GroupsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGroups::route('/'),
            'create' => CreateGroup::route('/create'),
            'edit' => EditGroup::route('/{record}/edit'),
        ];
    }
}