<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriBlogResource\Pages;
use App\Models\KategoriBlog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class KategoriBlogResource extends Resource
{
    protected static ?string $model = KategoriBlog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Blog'; // opsional: kelompokkan di sidebar

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('nama_kategori')->searchable(),
                TextColumn::make('created_at')->dateTime()
            ])
            ->filters([
                //
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
            'index' => Pages\ListKategoriBlogs::route('/'),
            'create' => Pages\CreateKategoriBlog::route('/create'),
            'edit' => Pages\EditKategoriBlog::route('/{record}/edit'),
            'trash' => Pages\ListTrashedKategoriBlogs::route('/trash'),
        ];
    }
}
