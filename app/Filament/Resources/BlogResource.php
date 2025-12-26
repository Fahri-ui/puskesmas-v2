<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\Action;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('judul')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),

                Textarea::make('isi')
                    ->label('Isi Blog')
                    ->required()
                    ->columnSpanFull(),

                FileUpload::make('gambar')
                    ->label('Gambar Sampul')
                    ->image()
                    ->directory('blog-images')
                    ->visibility('public')
                    ->preserveFilenames(false)
                    ->maxSize(2048)
                    ->imageEditor(),

                Select::make('kategori_id')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori')
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draf' => 'Draf',
                        'publish' => 'Publish',
                        'arsip' => 'Arsip',
                    ])
                    ->required()
                    ->default('draf'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar')
                    ->label('sampul')
                    ->circular(false)
                    ->size(50),
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('penulis.name')
                    ->label('Penulis')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draf' => 'gray',
                        'publish' => 'success',
                        'arsip' => 'warning',
                        default => 'primary',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draf' => 'Draf',
                        'publish' => 'Publish',
                        'arsip' => 'Arsip',
                    ]),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
            'trash' => Pages\ListTrashedBlogs::route('/trash'),
        ];
    }
}
