<?php

namespace App\Filament\Resources\LayananResource\Pages;

use App\Filament\Resources\LayananResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\ForceDeleteAction;

class ListTrashedLayanans extends ListRecords
{
    protected static string $resource = LayananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali ke Daftar')
                ->url(LayananResource::getUrl('index'))
                ->button(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('icon')
                    ->label('Icon')
                    ->size(20),

                TextColumn::make('nama')
                    ->label('Nama Layanan')
                    ->weight('medium'),

                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(40),

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