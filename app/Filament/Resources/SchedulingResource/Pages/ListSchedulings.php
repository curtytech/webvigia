<?php

namespace App\Filament\Resources\SchedulingResource\Pages;

use App\Filament\Resources\SchedulingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchedulings extends ListRecords
{
    protected static string $resource = SchedulingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo agendamento'),
        ];
    }
}
