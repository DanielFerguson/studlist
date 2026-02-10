<?php

namespace App\Filament\Resources;

use App\Enums\AustralianState;
use App\Enums\ServiceType;
use App\Filament\Resources\ServiceListingResource\Pages;
use App\Models\ServiceListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ServiceListingResource extends Resource
{
    protected static ?string $model = ServiceListing::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Listings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\CheckboxList::make('types')
                    ->options(ServiceType::toSelectOptions())
                    ->required()
                    ->columns(2),
                Forms\Components\TextInput::make('abn')
                    ->maxLength(14),
                Forms\Components\TextInput::make('business_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_contact')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('email_contact')
                    ->email()
                    ->maxLength(255),
                Forms\Components\CheckboxList::make('locations_covered')
                    ->options(AustralianState::toSelectOptions())
                    ->required()
                    ->columns(2),
                Forms\Components\Repeater::make('links')
                    ->simple(
                        Forms\Components\TextInput::make('url')
                            ->url()
                            ->maxLength(255)
                    )
                    ->maxItems(5),
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
                Tables\Columns\TextColumn::make('business_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('types')
                    ->label('Types')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : ($state ?? '')),
                Tables\Columns\TextColumn::make('contact_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('locations_covered')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),
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
                    ->label('Type')
                    ->options(ServiceType::toSelectOptions())
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if (! is_string($value) || $value === '') {
                            return $query;
                        }

                        return $query->whereJsonContains('types', $value);
                    }),
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
            'index' => Pages\ListServiceListings::route('/'),
            'create' => Pages\CreateServiceListing::route('/create'),
            'edit' => Pages\EditServiceListing::route('/{record}/edit'),
        ];
    }
}
