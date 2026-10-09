<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $modelLabel = 'veículo';

    protected static ?string $pluralModelLabel = 'veículos';

    protected static ?string $navigationLabel = 'Veículos';

    protected static ?string $navigationGroup = 'Cadastros';

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'default' => 1,
                'md' => 2,
            ])
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Cadastrado por')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('person_id')
                    ->label('Pessoa vinculada')
                    ->relationship('person', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\TextInput::make('plate')
                    ->label('Placa')
                    ->mask('aaa-9999')
                    ->regex('/^[A-Z]{3}-[0-9]{4}$/i')
                    ->validationMessages([
                        'regex' => 'Informe a placa no formato ABC-1234.',
                    ])
                    ->dehydrateStateUsing(fn (string $state): string => strtoupper($state))
                    ->required()
                    ->maxLength(8)
                    ->helperText('Formato: ABC-1234'),
                Forms\Components\TextInput::make('model')
                    ->label('Modelo')
                    ->maxLength(255),
                Forms\Components\Select::make('color')
                    ->label('Cor')
                    ->options([
                        'Preto' => 'Preto',
                        'Branco' => 'Branco',
                        'Prata' => 'Prata',
                        'Cinza' => 'Cinza',
                        'Vermelho' => 'Vermelho',
                        'Azul' => 'Azul',
                        'Verde' => 'Verde',
                        'Amarelo' => 'Amarelo',
                        'Marrom' => 'Marrom',
                        'Bege' => 'Bege',
                        'Laranja' => 'Laranja',
                        'Roxo' => 'Roxo',
                        'Outra' => 'Outra',
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plate')
                    ->label('Placa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('model')
                    ->label('Modelo')
                    ->searchable()
                    ->sortable()
                    ->visibleFrom('md'),
                Tables\Columns\TextColumn::make('color')
                    ->label('Cor')
                    ->searchable()
                    ->visibleFrom('md'),
                Tables\Columns\TextColumn::make('person.name')
                    ->label('Pessoa vinculada')
                    ->searchable()
                    ->placeholder('Sem vínculo')
                    ->visibleFrom('md'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cadastrado por')
                    ->searchable()
                    ->visibleFrom('lg'),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar')
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Excluir selecionados')
                        ->modalHeading('Excluir veículos selecionados?')
                        ->modalSubmitActionLabel('Excluir')
                        ->modalCancelActionLabel('Cancelar'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}
