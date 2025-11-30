<?php

namespace App\Filament\Resources;

use App\Enums\AustralianState;
use App\Enums\Breed;
use App\Enums\GeneticsType;
use App\Filament\Resources\GeneticsListingResource\Pages;
use App\Models\GeneticsListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GeneticsListingResource extends Resource
{
    protected static ?string $model = GeneticsListing::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Listings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('photos')
                    ->multiple()
                    ->image()
                    ->directory('listings/genetics')
                    ->maxFiles(10)
                    ->reorderable(),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                Forms\Components\Select::make('breed')
                    ->options(Breed::toSelectOptions())
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options(GeneticsType::toSelectOptions())
                    ->required(),
                Forms\Components\TextInput::make('sire')
                    ->maxLength(255),
                Forms\Components\TextInput::make('dam')
                    ->maxLength(255),
                Forms\Components\TextInput::make('registration_link')
                    ->url()
                    ->maxLength(255),
                Forms\Components\Select::make('storage_location')
                    ->options(AustralianState::toSelectOptions())
                    ->required(),
                Forms\Components\TextInput::make('phone_contact')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email_contact')
                    ->email()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('breed')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'Semen Straws',
                        'success' => 'Embryos',
                    ]),
                Tables\Columns\TextColumn::make('price')
                    ->money('AUD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('storage_location')
                    ->label('Storage'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(GeneticsType::toSelectOptions()),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('breed')
                    ->options(Breed::toSelectOptions()),
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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListGeneticsListings::route('/'),
            'create' => Pages\CreateGeneticsListing::route('/create'),
            'edit' => Pages\EditGeneticsListing::route('/{record}/edit'),
        ];
    }
}
