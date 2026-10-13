<?php

namespace App\Filament\Resources\EntryResource\Pages;

use App\Filament\Resources\EntryResource;
use App\Models\Entry;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class CreateEntry extends CreateRecord
{
    protected static string $resource = EntryResource::class;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Salvar entrada');
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Salvar e adicionar outra');
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Cancelar');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $openEntry = null;

        if (filled($data['person_id'] ?? null)) {
            $openEntry = Entry::query()
                ->where('person_id', $data['person_id'])
                ->whereNull('exit_time')
                ->latest('entry_time')
                ->first();
        }

        if (! $openEntry && filled($data['car_id'] ?? null)) {
            $openEntry = Entry::query()
                ->where('car_id', $data['car_id'])
                ->whereNull('exit_time')
                ->latest('entry_time')
                ->first();
        }

        if ($openEntry) {
            Notification::make()
                ->warning()
                ->title('Já existe uma entrada sem saída')
                ->body('Atualize o registro existente para cadastrar a saída.')
                ->send();

            $this->redirect(
                EntryResource::getUrl('edit', ['record' => $openEntry]),
                navigate: true,
            );

            throw new Halt;
        }

        if (auth()->user()?->role === 'watchman') {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }
}
