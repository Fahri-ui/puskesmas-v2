<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LayananResource\Pages;
use App\Models\Layanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Support\Str;
use Filament\Tables\Actions\Action;

class LayananResource extends Resource
{
    protected static ?string $model = Layanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Pelayanan';

    protected static ?string $navigationLabel = 'Layanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Layanan')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                        $operation === 'create' 
                            ? $set('slug', Str::slug($state)) 
                            : null
                    ),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Layanan::class, 'slug', ignoreRecord: true),

                TextInput::make('icon')
                    ->label('Icon (Heroicons)')
                    ->helperText('Contoh: heroicon-o-heart, heroicon-o-clock. Lihat di heroicons.com')
                    ->maxLength(255)
                    ->nullable()
                    ->columnSpanFull(),

                Textarea::make('deskripsi')
                    ->label('Deskripsi')
                    ->nullable()
                    ->columnSpanFull(),

                Toggle::make('aktif')
                    ->label('Aktif?')
                    ->default(true)
                    ->required(),

                TextInput::make('urutan')
                    ->label('Urutan Tampil')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->helperText('Angka lebih kecil = tampil lebih awal'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('icon')
                    ->label('Icon')
                    ->size(20), // ukuran ikon di tabel

                TextColumn::make('nama')
                    ->label('Nama Layanan')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50),

                IconColumn::make('aktif')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('aktif')
                    ->label('Status Aktif')
                    ->nullable(),
            ])
            ->headerActions([
                Action::make('trash')
                    ->label('Recycle Bin')
                    ->url(static::getUrl('trash'))
                    ->icon('heroicon-o-trash')
                    ->color('warning'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('urutan', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLayanans::route('/'),
            'create' => Pages\CreateLayanan::route('/create'),
            'edit' => Pages\EditLayanan::route('/{record}/edit'),
            'trash' => Pages\ListTrashedLayanans::route('/trash'),
        ];
    }
}