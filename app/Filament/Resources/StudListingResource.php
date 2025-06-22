<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudListingResource\Pages;
use App\Models\StudListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudListingResource extends Resource
{
    protected static ?string $model = StudListing::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

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
                Forms\Components\DatePicker::make('date_of_birth'),
                Forms\Components\TextInput::make('breed')
                    ->maxLength(255),
                Forms\Components\TextInput::make('colour')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tattoo_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sire')
                    ->maxLength(255),
                Forms\Components\TextInput::make('dam')
                    ->maxLength(255),
                Forms\Components\TextInput::make('registration_link')
                    ->url()
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
                Tables\Columns\TextColumn::make('tattoo_number')
                    ->label('Tattoo #'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'active',
                    ]),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('breed')
                    ->options([
                        'Angus' => 'Angus',
                        'Hereford' => 'Hereford',
                        'Charolais' => 'Charolais',
                        'Brahman' => 'Brahman',
                        'Other' => 'Other',
                    ]),
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
            'index' => Pages\ListStudListings::route('/'),
            'create' => Pages\CreateStudListing::route('/create'),
            'edit' => Pages\EditStudListing::route('/{record}/edit'),
        ];
    }
}
