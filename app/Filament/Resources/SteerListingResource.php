<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SteerListingResource\Pages;
use App\Models\SteerListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SteerListingResource extends Resource
{
    protected static ?string $model = SteerListing::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

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
                    ->directory('listings/steers')
                    ->maxFiles(10)
                    ->reorderable(),
                Forms\Components\DatePicker::make('date_of_birth'),
                Forms\Components\TextInput::make('breed')
                    ->maxLength(255),
                Forms\Components\TextInput::make('colour')
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sire')
                    ->maxLength(255),
                Forms\Components\TextInput::make('dam')
                    ->maxLength(255),
                Forms\Components\TextInput::make('business_contact')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_contact')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email_contact')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pic_number')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->rows(3),
                Forms\Components\Toggle::make('started_on_feed')
                    ->label('Started on Feed'),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('stripe_subscription_id')
                    ->maxLength(255)
                    ->disabled(),
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
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('price')
                    ->money('AUD')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'active',
                    ]),
                Tables\Columns\IconColumn::make('started_on_feed')
                    ->boolean()
                    ->label('On Feed'),
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
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                    ]),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable(),
                Tables\Filters\TernaryFilter::make('started_on_feed')
                    ->label('Started on Feed'),
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
            'index' => Pages\ListSteerListings::route('/'),
            'create' => Pages\CreateSteerListing::route('/create'),
            'edit' => Pages\EditSteerListing::route('/{record}/edit'),
        ];
    }
}
