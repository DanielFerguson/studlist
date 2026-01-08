<?php

namespace App\Filament\Resources;

use App\Enums\BaleType;
use App\Enums\HayPriceType;
use App\Enums\HayQualityGrade;
use App\Enums\HayType;
use App\Enums\NitrateLevel;
use App\Enums\SeasonCut;
use App\Enums\StorageType;
use App\Filament\Resources\HayListingResource\Pages;
use App\Models\HayListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HayListingResource extends Resource
{
    protected static ?string $model = HayListing::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Listings';

    protected static ?string $navigationLabel = 'Hay';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
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
                            ->directory('hay-photos')
                            ->maxFiles(10)
                            ->reorderable(),
                        Forms\Components\Select::make('hay_type')
                            ->options(HayType::toSelectOptions())
                            ->required(),
                        Forms\Components\Select::make('bale_type')
                            ->options(BaleType::toSelectOptions())
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('weight_per_bale')
                            ->numeric()
                            ->suffix('kg'),
                        Forms\Components\Select::make('season_cut')
                            ->options(SeasonCut::toSelectOptions()),
                        Forms\Components\TextInput::make('cut_year')
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(date('Y') + 1),
                    ])->columns(2),

                Forms\Components\Section::make('Quality & Testing')
                    ->schema([
                        Forms\Components\Select::make('quality_grade')
                            ->options(HayQualityGrade::toSelectOptions()),
                        Forms\Components\Toggle::make('test_results_available'),
                        Forms\Components\TextInput::make('protein_percentage')
                            ->numeric()
                            ->suffix('%'),
                        Forms\Components\TextInput::make('moisture_percentage')
                            ->numeric()
                            ->suffix('%'),
                        Forms\Components\TextInput::make('energy_mj_kg')
                            ->numeric()
                            ->suffix('MJ/kg'),
                        Forms\Components\Select::make('nitrate_level')
                            ->options(NitrateLevel::toSelectOptions()),
                        Forms\Components\Toggle::make('weather_damaged'),
                    ])->columns(2),

                Forms\Components\Section::make('Storage & Location')
                    ->schema([
                        Forms\Components\Select::make('storage_type')
                            ->options(StorageType::toSelectOptions())
                            ->required(),
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('latitude')
                            ->numeric(),
                        Forms\Components\TextInput::make('longitude')
                            ->numeric(),
                        Forms\Components\Toggle::make('delivery_available'),
                        Forms\Components\TextInput::make('delivery_radius_km')
                            ->numeric()
                            ->suffix('km'),
                        Forms\Components\TextInput::make('minimum_order_quantity')
                            ->numeric(),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\Select::make('price_type')
                            ->options(HayPriceType::toSelectOptions())
                            ->required(),
                        Forms\Components\TextInput::make('price_per_bale')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('price_per_tonne')
                            ->numeric()
                            ->prefix('$'),
                    ])->columns(3),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('business_contact')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone_contact')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email_contact')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('pic_number')
                            ->maxLength(20),
                    ])->columns(2),

                Forms\Components\Section::make('Description')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->maxLength(2000),
                    ]),
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
                    ->sortable()
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('hay_type')
                    ->colors([
                        'success' => 'Lucerne',
                        'primary' => 'Grass',
                        'warning' => 'Oaten',
                        'info' => 'Mixed',
                    ]),
                Tables\Columns\TextColumn::make('bale_type'),
                Tables\Columns\TextColumn::make('quantity')
                    ->sortable()
                    ->suffix(' bales'),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->limit(20),
                Tables\Columns\IconColumn::make('delivery_available')
                    ->boolean(),
                Tables\Columns\IconColumn::make('test_results_available')
                    ->boolean(),
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
                Tables\Filters\SelectFilter::make('hay_type')
                    ->options(HayType::toSelectOptions()),
                Tables\Filters\SelectFilter::make('bale_type')
                    ->options(BaleType::toSelectOptions()),
                Tables\Filters\SelectFilter::make('quality_grade')
                    ->options(HayQualityGrade::toSelectOptions()),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable(),
                Tables\Filters\TernaryFilter::make('delivery_available'),
                Tables\Filters\TernaryFilter::make('test_results_available'),
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
            'index' => Pages\ListHayListings::route('/'),
            'create' => Pages\CreateHayListing::route('/create'),
            'edit' => Pages\EditHayListing::route('/{record}/edit'),
        ];
    }
}
