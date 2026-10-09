<?php

namespace App\Filament\Resources\CarResource\Pages;

use App\Filament\Resources\CarResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditCar extends EditRecord
{
    protected static string $resource = CarResource::class;

    public function getTitle(): string
    {
        return 'Editar veículo';
    }

    public function getBreadcrumb(): string
    {
        return 'Editar';
    }

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
                ->modalHeading('Excluir este veículo?')
                ->modalSubmitActionLabel('Excluir')
                ->modalCancelActionLabel('Cancelar'),
        ];
    }
}
