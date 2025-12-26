<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;

class ListTrashedBlogs extends ListRecords
{
    protected static string $resource = BlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali ke Daftar')
                ->url(BlogResource::getUrl('index'))
                ->button(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')
                    ->label('Sampul')
                    ->circular(false)
                    ->size(50),

                TextColumn::make('judul')
                    ->label('Judul')
                    ->limit(30),

                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori'),

                TextColumn::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime(),
            ])
            ->actions([
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return static::getResource()::getEloquentQuery()->onlyTrashed();
    }
}