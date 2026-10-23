<?php

namespace App\Filament\Resources\SchedulingResource\Pages;

use App\Filament\Resources\SchedulingResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditScheduling extends EditRecord
{
    protected static string $resource = SchedulingResource::class;

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Salvar alterações');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Cancelar');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Excluir')
                ->modalHeading('Excluir este agendamento?')
                ->modalSubmitActionLabel('Excluir')
                ->modalCancelActionLabel('Cancelar'),
        ];
    }
}
