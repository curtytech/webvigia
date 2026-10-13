<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use App\Models\Car;
use App\Models\Entry;
use App\Models\Person;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $modelLabel = 'log de auditoria';

    protected static ?string $pluralModelLabel = 'logs de auditoria';

    protected static ?string $navigationLabel = 'Logs de auditoria';

    protected static ?string $navigationGroup = 'Administração';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canViewAny(): bool
    {
        return static::canAccess();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event')
                    ->label('Ação')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'created' => 'Criado',
                        'updated' => 'Alterado',
                        'deleted' => 'Excluído',
                        default => $state,
                    })
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('auditable_type')
                    ->label('Registro')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        User::class => 'Usuário',
                        Person::class => 'Pessoa',
                        Car::class => 'Veículo',
                        Entry::class => 'Entrada',
                        default => class_basename($state),
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('auditable_id')
                    ->label('ID do registro')
                    ->sortable(),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Responsável')
                    ->placeholder('Sistema ou usuário removido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('old_values')
                    ->label('Valores anteriores')
                    ->formatStateUsing(fn (mixed $state): string => static::formatValues($state))
                    ->wrap()
                    ->limit(120)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('new_values')
                    ->label('Valores novos')
                    ->formatStateUsing(fn (mixed $state): string => static::formatValues($state))
                    ->wrap()
                    ->limit(120)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data e hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->label('Ação')
                    ->options([
                        'created' => 'Criado',
                        'updated' => 'Alterado',
                        'deleted' => 'Excluído',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([])
            ->bulkActions([]);
    }

    private static function formatValues(mixed $values): string
    {
        if ($values === null) {
            return '—';
        }

        if (is_string($values)) {
            $decoded = json_decode($values, true);

            if (is_array($decoded)) {
                $values = $decoded;
            } else {
                return $values;
            }
        }

        if (! is_array($values)) {
            return (string) $values;
        }

        return (string) json_encode(
            $values,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
        ];
    }
}
