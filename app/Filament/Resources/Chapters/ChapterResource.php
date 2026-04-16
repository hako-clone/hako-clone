<?php

namespace App\Filament\Resources\Chapters;

use App\Models\Volume;
use Filament\Forms;
use Filament\Schemas;
use Filament\Tables;
use App\Filament\Resources\Chapters\Pages\CreateChapter;
use App\Filament\Resources\Chapters\Pages\EditChapter;
use App\Filament\Resources\Chapters\Pages\ListChapters;
use App\Models\Chapter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChapterResource extends Resource
{
    protected static ?string $model = Chapter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make('Thông tin Chương')
                    ->schema([
                        // BƯỚC 1: Chọn Truyện
                        Forms\Components\Select::make('novel_id')
                            ->label('1. Chọn Truyện trước')
                            ->options(\App\Models\Novel::pluck('title', 'id'))
                            ->searchable()
                            ->live() 
                            ->dehydrated(false) 
                            ->afterStateHydrated(function ($set, $record) {
                                if ($record && $record->volume) {
                                    $set('novel_id', $record->volume->novel_id);
                                }
                            })
                            ->afterStateUpdated(fn ($set) => $set('volume_id', null)), 

                        // BƯỚC 2: Chọn Tập (Dựa trên Truyện)
                        Forms\Components\Select::make('volume_id')
                            ->label('2. Chọn Tập')
                            ->options(fn ($get) => \App\Models\Volume::query()
                                ->where('novel_id', $get('novel_id')) 
                                ->pluck('title', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->disabled(fn ($get) => ! $get('novel_id')), 
                        // Tên Chương
                        Forms\Components\TextInput::make('title')
                            ->label('Tên chương (VD: Chương 1: Sự khởi đầu)')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true) // Bật tính năng tự động tạo slug
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state ?? ''))),
                        
                        // THÊM Ô SLUG BỊ THIẾU VÀO ĐÂY
                        Forms\Components\TextInput::make('slug')
                            ->label('Đường dẫn (Slug)')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),

                // KHU VỰC ALL-IN-ONE (TRUYỆN CHỮ & TRUYỆN TRANH)
                Schemas\Components\Section::make('Nội dung Chương (All-in-one)')
                    ->schema([
                        Forms\Components\Builder::make('content')
                            ->label('Xây dựng nội dung')
                            ->blocks([
                                // 🟢 KHỐI 1: TRUYỆN CHỮ
                                Forms\Components\Builder\Block::make('text_block')
                                    ->label('Viết chữ (Truyện Chữ / Light Novel)')
                                    ->icon('heroicon-m-bars-3-bottom-left')
                                    ->schema([
                                        Forms\Components\RichEditor::make('text_content')
                                            ->label('Nội dung văn bản')
                                            ->required()
                                            ->fileAttachmentsDirectory('chapters/images') 
                                            ->toolbarButtons([
                                                'attachFiles', // Đã gọi lại nút Up ảnh
                                                'blockquote', 'bold', 'bulletList', 'h2', 'h3', 'italic',
                                                'link', 'orderedList', 'redo', 'strike', 'underline', 'undo',
                                            ]),
                                    ]),

                                // 🟢 KHỐI 2: TRUYỆN TRANH
                                Forms\Components\Builder\Block::make('comic_block')
                                    ->label('Up ảnh hàng loạt (Truyện Tranh)')
                                    ->icon('heroicon-m-photo')
                                    ->schema([
                                        Forms\Components\FileUpload::make('pages')
                                            ->label('Tải các trang truyện lên (Ấn Ctrl + A để chọn tất cả)')
                                            ->image()
                                            ->multiple()
                                            ->reorderable()
                                            ->appendFiles()
                                            ->disk('public')
                                            ->directory('chapters/pages')
                                            ->panelLayout('grid'),
                                    ]),
                            ])
                            ->columnSpanFull() 
                            ->collapsible(), 
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('volume.novel.title')
                    ->label('Thuộc truyện')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('volume.title')
                    ->label('Thuộc tập')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tên chương')
                    ->searchable(),
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
            'index' => ListChapters::route('/'),
            'create' => CreateChapter::route('/create'),
            'edit' => EditChapter::route('/{record}/edit'),
        ];
    }
}