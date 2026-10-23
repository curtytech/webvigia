<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchedulingResource\Pages;
use App\Models\Scheduling;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SchedulingResource extends Resource
{
    protected static ?string $model = Scheduling::class;

    protected static ?string $modelLabel = 'agendamento';

    protected static ?string $pluralModelLabel = 'agendamentos';

    protected static ?string $navigationLabel = 'Agendamentos';

    protected static ?string $navigationGroup = 'Controle de acesso';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()?->role !== 'watchman';
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()?->role !== 'watchman';
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()?->role !== 'watchman';
    }

    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()?->role !== 'watchman';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'default' => 1,
                'md' => 2,
            ])
            ->schema([
                Forms\Components\TextInput::make('responsible_name')
                    ->label('Nome do responsável')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('responsible_email')
                    ->label('E-mail do responsável')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->label('Telefone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('people_quantity')
                    ->label('Quantidade de pessoas')
                    ->integer()
                    ->minValue(1)
                    ->required(),
                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->label('Data e horário')
                    ->required()
                    ->minDate(now()->startOfDay()->addDay()),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pendente',
                        'confirmed' => 'Confirmado',
                        'cancelled' => 'Cancelado',
                    ]),
                Forms\Components\Textarea::make('motivation')
                    ->label('Motivo do agendamento')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('responsible_name')
                    ->label('Responsável')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsible_email')
                    ->label('E-mail')
                    ->searchable()
                    ->visibleFrom('md'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Telefone')
                    ->visibleFrom('md'),
                Tables\Columns\TextColumn::make('people_quantity')
                    ->label('Pessoas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Agendado para')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar')
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Excluir selecionados')
                        ->modalHeading('Excluir agendamentos selecionados?')
                        ->modalSubmitActionLabel('Excluir')
                        ->modalCancelActionLabel('Cancelar'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedulings::route('/'),
            'create' => Pages\CreateScheduling::route('/create'),
            'edit' => Pages\EditScheduling::route('/{record}/edit'),
        ];
    }
}
