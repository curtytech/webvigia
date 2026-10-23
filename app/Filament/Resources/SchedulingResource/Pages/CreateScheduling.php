<?php

namespace App\Filament\Resources\SchedulingResource\Pages;

use App\Filament\Resources\SchedulingResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateScheduling extends CreateRecord
{
    protected static string $resource = SchedulingResource::class;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Salvar agendamento');
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Salvar e adicionar outro');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Cancelar');
    }
}
