<?php

namespace App\Filament\Resources\Volumes;

use App\Filament\Resources\Volumes\Pages\CreateVolume;
use App\Filament\Resources\Volumes\Pages\EditVolume;
use App\Filament\Resources\Volumes\Pages\ListVolumes;
use App\Filament\Resources\Volumes\Schemas\VolumeForm;
use App\Filament\Resources\Volumes\Tables\VolumesTable;
use App\Models\Volume;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Schemas;
use Filament\Tables;

class VolumeResource extends Resource
{
    protected static ?string $model = Volume::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make('Thông tin Tập')
                    ->schema([
                        Forms\Components\Select::make('novel_id')
                            ->label('Thuộc bộ truyện')
                            ->options(\App\Models\Novel::pluck('title', 'id'))
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label('Tên tập (VD: Tập 1, Tập 2...)')
                            ->required()
                            ->maxLength(255),

                        // CHỈ GIỮ LẠI 1 Ô TẢI ẢNH (Bản nâng cấp)
                        Forms\Components\FileUpload::make('cover_image')
                            ->label('Tải ảnh bìa lên (Tự động đọc thông tin)')
                            ->image()
                            ->disk('public') 
                            ->directory('volumes/covers')
                            ->live(onBlur: true) 
                            ->afterStateUpdated(function ($set, $state) {
                                // 🌟 DÒNG QUAN TRỌNG: Nếu không phải đối tượng file thì dừng lại luôn
                                if (! $state instanceof \Illuminate\Http\UploadedFile) {
                                    return;
                                }

                                // Trích xuất tên tệp ảnh (Chỉ chạy khi $state thực sự là file)
                                $set('title', 'Tập - ' . $state->getClientOriginalName());

                                // Trích xuất tóm tắt cơ bản
                                $fileSize = round($state->getSize() / 1024 / 1024, 2) . ' MB';
                                $fileType = $state->getClientMimeType();
                                $set('description', "Đây là ảnh bìa kiểu $fileType có kích thước là $fileSize.");
                            }),

                        // CHỈ GIỮ LẠI 1 Ô TÓM TẮT
                        Forms\Components\Textarea::make('description')
                            ->label('Tóm tắt Tập')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Ảnh bìa'),
                
                Tables\Columns\TextColumn::make('novel.title')
                    ->label('Thuộc truyện')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Tên tập')
                    ->searchable(),
            ])
            ->filters([
                //
            ]);
            // Tôi đã xóa phần ->actions(...) đi rồi nhé!
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
            'index' => ListVolumes::route('/'),
            'create' => CreateVolume::route('/create'),
            'edit' => EditVolume::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    $query = parent::getEloquentQuery();
    $user = \Illuminate\Support\Facades\Auth::user();

    // 1. Trùm Cuối thấy tất cả
    if ($user->role === 'super_admin') {
        return $query;
    }

    // 2. Admin chỉ thấy Volume của những bộ Novel thuộc nhóm mình
    if ($user->role === 'admin') {
        return $query->whereHas('novel', function ($q) use ($user) {
            $q->where('group_id', $user->group_id);
        });
    }

    return $query->where('id', 0);
}
}
