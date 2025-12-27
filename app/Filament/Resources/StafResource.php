<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StafResource\Pages;
use App\Models\Staf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\Action;

class StafResource extends Resource
{
    protected static ?string $model = Staf::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Kepegawaian'; // opsional: kelompokkan di sidebar

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                TextInput::make('nip')
                    ->label('NIP (Nomor Induk Pegawai)')
                    ->required()
                    ->maxLength(50)
                    ->unique(Staf::class, 'nip', ignoreRecord: true),

                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->required()
                    ->maxLength(255),

                TextInput::make('no_telepon')
                    ->label('No. Telepon')
                    ->tel()
                    ->maxLength(20)
                    ->nullable(),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255)
                    ->nullable()
                    ->unique(Staf::class, 'email', ignoreRecord: true),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->nullable()
                    ->columnSpanFull(),

                FileUpload::make('foto')
                    ->label('Foto Profil')
                    ->image()
                    ->directory('staf-images')
                    ->visibility('public')
                    ->preserveFilenames(false)
                    ->maxSize(2048) // maks 2MB
                    ->imageEditor(),

                DatePicker::make('tgl_lahir')
                    ->label('Tanggal Lahir')
                    ->required()
                    ->maxDate(now()), // tidak boleh tanggal masa depan

                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'laki-laki' => 'Laki-laki',
                        'perempuan' => 'Perempuan',
                    ])
                    ->required(),

                Select::make('status')
                    ->label('Status Pegawai')
                    ->options([
                        'aktif' => 'Aktif',
                        'pensiun' => 'Pensiun',
                    ])
                    ->required()
                    ->default('aktif'),
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular()
                    ->size(40),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_kelamin')
                    ->label('JK')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Laki-laki' => 'primary',
                        'Perempuan' => 'pink',
                    }),

                TextColumn::make('status_pegawai')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'tidak_aktif' => 'warning',
                        'pensiun' => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->options([
                        'laki-laki' => 'Laki-laki',
                        'perempuan' => 'Perempuan',
                    ]),

                Tables\Filters\SelectFilter::make('status_pegawai')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak_aktif' => 'Tidak Aktif',
                        'pensiun' => 'Pensiun',
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
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStafs::route('/'),
            'create' => Pages\CreateStaf::route('/create'),
            'edit' => Pages\EditStaf::route('/{record}/edit'),
            'trash' => Pages\ListTrashedStafs::route('/trash'),
        ];
    }
}