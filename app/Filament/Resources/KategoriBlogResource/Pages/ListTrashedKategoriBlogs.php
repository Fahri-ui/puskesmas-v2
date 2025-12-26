<?php

namespace App\Filament\Resources\KategoriBlogResource\Pages;

use App\Filament\Resources\KategoriBlogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;

class ListTrashedKategoriBlogs extends ListRecords
{
    protected static string $resource = KategoriBlogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali ke Daftar')
                ->url(KategoriBlogResource::getUrl('index'))
                ->button(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('nama_kategori'),
                TextColumn::make('deleted_at')->dateTime(), // kapan dihapus
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