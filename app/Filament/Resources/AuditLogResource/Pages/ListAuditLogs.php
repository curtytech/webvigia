<?php

namespace App\Filament\Resources\AuditLogResource\Pages;

use App\Filament\Resources\AuditLogResource;
use Filament\Resources\Pages\ListRecords;

class ListAuditLogs extends ListRecords
{
    protected static string $resource = AuditLogResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return AuditLogResource::canAccess();
    }

    protected function authorizeAccess(): void
    {
        abort_unless(AuditLogResource::canAccess(), 403);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
