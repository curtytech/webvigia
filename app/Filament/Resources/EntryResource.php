<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntryResource\Pages;
use App\Models\Car;
use App\Models\Entry;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class EntryResource extends Resource
{
    protected static ?string $model = Entry::class;

    protected static ?string $modelLabel = 'Entrada e saída';

    protected static ?string $navigationLabel = 'Entradas e saídas';

    protected static ?string $navigationGroup = 'Controle de acesso';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'watchman'], true);
    }

    public static function canEdit(Model $record): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'watchman'], true);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'default' => 1,
                'md' => 2,
            ])
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Registrado por')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => auth()->user()?->role === 'watchman' ? auth()->id() : null)
                    ->disabled(fn (): bool => auth()->user()?->role === 'watchman')
                    ->dehydrated()
                    ->required(),
                Forms\Components\Select::make('person_id')
                    ->label('Pessoa')
                    ->relationship('person', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição'),
                    ])
                    ->createOptionAction(fn (Forms\Components\Actions\Action $action) => $action
                        ->label('Cadastrar pessoa')
                        ->modalHeading('Nova pessoa')
                        ->modalSubmitActionLabel('Salvar pessoa'))
                    ->createOptionUsing(fn (array $data) => Person::query()->create([
                        ...$data,
                        'user_id' => auth()->id(),
                    ])->getKey())
                    ->nullable(),
                Forms\Components\Select::make('car_id')
                    ->label('Veículo')
                    ->relationship('car', 'plate')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('plate')
                            ->label('Placa')
                            ->mask('aaa-9999')
                            ->regex('/^[A-Z]{3}-[0-9]{4}$/i')
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
                            ->label('Descrição'),
                    ])
                    ->createOptionAction(fn (Forms\Components\Actions\Action $action) => $action
                        ->label('Cadastrar veículo')
                        ->modalHeading('Novo veículo')
                        ->modalSubmitActionLabel('Salvar veículo'))
                    ->createOptionUsing(fn (array $data) => Car::query()->create([
                        ...$data,
                        'plate' => strtoupper($data['plate']),
                        'user_id' => auth()->id(),
                    ])->getKey())
                    ->nullable(),
                Forms\Components\DateTimePicker::make('entry_time')
                    ->label('Horário de entrada')
                    ->default(now())
                    ->required(),
                Forms\Components\DateTimePicker::make('exit_time')
                    ->label('Horário de saída')
                    ->nullable(),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('person.name')
                    ->label('Pessoa')
                    ->searchable()
                    ->placeholder('Não informada'),
                Tables\Columns\TextColumn::make('car.plate')
                    ->label('Veículo')
                    ->searchable()
                    ->placeholder('Não informado'),
                Tables\Columns\TextColumn::make('entry_time')
                    ->label('Entrada')
                    ->dateTime('d/m H:i')
                    ->sortable()
                    ->visibleFrom('md'),
                Tables\Columns\TextColumn::make('exit_time')
                    ->label('Saída')
                    ->dateTime('d/m H:i')
                    ->placeholder('Em aberto')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Registrado por')
                    ->searchable()
                    ->visibleFrom('lg'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('registerExit')
                    ->label('Registrar saída')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('success')
                    ->authorize(fn (Entry $record): bool => static::canEdit($record))
                    ->action(fn (Entry $record) => $record->update([
                        'exit_time' => now(),
                    ]))
                    ->successNotificationTitle('Saída registrada com sucesso.')
                    ->visible(fn (Entry $record): bool => $record->exit_time === null)
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->label('Editar')
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Excluir selecionadas')
                        ->modalHeading('Excluir entradas selecionadas?')
                        ->modalSubmitActionLabel('Excluir')
                        ->modalCancelActionLabel('Cancelar'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntries::route('/'),
            'create' => Pages\CreateEntry::route('/create'),
            'edit' => Pages\EditEntry::route('/{record}/edit'),
        ];
    }
}
