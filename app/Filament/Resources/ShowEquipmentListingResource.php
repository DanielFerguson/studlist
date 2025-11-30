<?php

namespace App\Filament\Resources;

use App\Enums\EquipmentCondition;
use App\Filament\Resources\ShowEquipmentListingResource\Pages;
use App\Models\ShowEquipmentListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShowEquipmentListingResource extends Resource
{
    protected static ?string $model = ShowEquipmentListing::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Listings';

    protected static ?string $navigationLabel = 'Show Equipment';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('photos')
                    ->multiple()
                    ->image()
                    ->directory('listings/equipment')
                    ->maxFiles(10)
                    ->reorderable(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->rows(3),
                Forms\Components\Select::make('condition')
                    ->options(EquipmentCondition::toSelectOptions())
                    ->required(),
                Forms\Components\TextInput::make('location')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_contact')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email_contact')
                    ->email()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\BadgeColumn::make('condition')
                    ->colors([
                        'success' => 'New',
                        'primary' => 'Like New',
                        'warning' => 'Good',
                        'danger' => 'Fair',
                        'gray' => 'Poor',
                    ]),
                Tables\Columns\TextColumn::make('location'),
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
                Tables\Filters\SelectFilter::make('condition')
                    ->options(EquipmentCondition::toSelectOptions()),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable(),
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
            'index' => Pages\ListShowEquipmentListings::route('/'),
            'create' => Pages\CreateShowEquipmentListing::route('/create'),
            'edit' => Pages\EditShowEquipmentListing::route('/{record}/edit'),
        ];
    }
}
