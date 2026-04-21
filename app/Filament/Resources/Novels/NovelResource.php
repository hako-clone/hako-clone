<?php

namespace App\Filament\Resources\Novels;

use App\Filament\Resources\Novels\Pages;
use App\Models\Novel;
use BackedEnum;
use Filament\Forms;        
use Filament\Schemas;      
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; // Đã thêm Auth

class NovelResource extends Resource
{
    protected static ?string $model = Novel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make('Thông tin cơ bản')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Tên truyện')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            
                        Forms\Components\TextInput::make('slug')
                            ->label('Đường dẫn (Slug)')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('author')
                            ->label('Tác giả')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('illustrator')
                            ->label('Họa sĩ')
                            ->maxLength(255),

                        Forms\Components\Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'ongoing' => 'Đang tiến hành',
                                'completed' => 'Đã hoàn thành',
                                'paused' => 'Tạm ngưng',
                            ])
                            ->default('ongoing'),

                        // 🌟 ĐÃ THÊM: Ô CHỌN NHÓM DỊCH
                        Forms\Components\Select::make('group_id')
                            ->label('Nhóm Dịch')
                            ->relationship('group', 'name')
                            ->preload()
                            ->searchable()
                            ->placeholder('Chọn nhóm...')
                            ->visible(fn () => Auth::user()?->role === 'super_admin'),

                        // Ô CHỌN THỂ LOẠI
                        Forms\Components\Select::make('categories')
                            ->label('Thể loại truyện')
                            ->relationship('categories', 'title') 
                            ->multiple() 
                            ->preload() 
                            ->searchable()
                            ->columnSpanFull(),
                    ])->columns(2),

                Schemas\Components\Section::make('Nội dung & Hình ảnh')
                    ->schema([
                        Forms\Components\FileUpload::make('cover_image')
                            ->label('Ảnh bìa')
                            ->image() 
                            ->directory('novels/covers'),

                        Forms\Components\Textarea::make('description')
                            ->label('Tóm tắt nội dung')
                            ->rows(5)
                            ->columnSpanFull(), 
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Ảnh bìa'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Tên truyện')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author')
                    ->label('Tác giả')
                    ->searchable(),
                
                // 🌟 HIỂN THỊ NHÓM DỊCH:
                // Hiện tên nhóm dịch dưới dạng Badge màu xanh (info). 
                // Chỉ Super Admin mới thấy cột này.
                Tables\Columns\TextColumn::make('group.name')
                    ->label('Nhóm Dịch')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->placeholder('Chưa có nhóm')
                    ->visible(fn () => Auth::user()?->role === 'super_admin'),
                
                Tables\Columns\TextColumn::make('categories.title')
                    ->label('Thể loại')
                    ->badge() 
                    ->separator(', '),

                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ongoing' => 'success',
                        'completed' => 'info',
                        'paused' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ]);
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
            'index' => Pages\ListNovels::route('/'),
            'create' => Pages\CreateNovel::route('/create'),
            'edit' => Pages\EditNovel::route('/{record}/edit'),
        ];
    }
    
    // 🌟 VŨ KHÍ TỐI THƯỢNG: BỘ LỌC DỮ LIỆU ĐA TẦNG
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = \Illuminate\Support\Facades\Auth::user();

        if ($user->role === 'super_admin') {
            return $query;
        }

        if ($user->role === 'admin') {
            return $query->where('group_id', $user->group_id);
        }

        return $query->where('id', 0);
    }
}