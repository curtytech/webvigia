<?php

use App\Filament\Resources\AuditLogResource;
use App\Filament\Resources\AuditLogResource\Pages\ListAuditLogs;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('shows the audit log menu and list to admins', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin);

    expect(AuditLogResource::shouldRegisterNavigation())->toBeTrue()
        ->and(AuditLogResource::canAccess())->toBeTrue();

    Livewire::test(ListAuditLogs::class)
        ->assertOk();
});

it('hides the audit log menu and denies access to non-admin roles', function (string $role) {
    $user = User::factory()->create(['role' => $role]);

    $this->actingAs($user);

    expect(AuditLogResource::shouldRegisterNavigation())->toBeFalse()
        ->and(AuditLogResource::canAccess())->toBeFalse();

    Livewire::test(ListAuditLogs::class)
        ->assertForbidden();
})->with([
    'managers' => 'manager',
    'watchmen' => 'watchman',
]);
