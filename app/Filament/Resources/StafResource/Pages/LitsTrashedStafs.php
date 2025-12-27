<?php

namespace App\Filament\Resources\StafResource\Pages;

use App\Filament\Resources\StafResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;

class ListTrashedStafs extends ListRecords
{
    protected static string $resource = StafResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali ke Daftar')
                ->url(StafResource::getUrl('index'))
                ->button(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->size(40),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->weight('medium'),

                TextColumn::make('nip')
                    ->label('NIP'),

                TextColumn::make('jabatan')
                    ->label('Jabatan'),

                TextColumn::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime('d M Y H:i'),
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