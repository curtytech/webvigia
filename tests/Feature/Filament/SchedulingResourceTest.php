<?php

use App\Filament\Resources\SchedulingResource\Pages\CreateScheduling;
use App\Filament\Resources\SchedulingResource\Pages\EditScheduling;
use App\Filament\Resources\SchedulingResource\Pages\ListSchedulings;
use App\Models\Scheduling;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('creates an agendamento from the Filament form', function () {
    $this->actingAs(User::factory()->create(['role' => 'admin']));

    Livewire::test(CreateScheduling::class)
        ->fillForm([
            'responsible_name' => 'Ana Silva',
            'responsible_email' => 'ana@example.com',
            'phone_number' => '11999999999',
            'motivation' => 'Visita agendada',
            'people_quantity' => 3,
            'scheduled_at' => '2026-10-25 10:30:00',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('scheduling', [
        'responsible_name' => 'Ana Silva',
        'responsible_email' => 'ana@example.com',
        'phone_number' => '11999999999',
        'motivation' => 'Visita agendada',
        'people_quantity' => 3,
        'scheduled_at' => '2026-10-25 10:30:00',
        'status' => 'pending',
    ]);
});

it('allows watchmen to view the scheduling list without write actions', function () {
    $watchman = User::factory()->create(['role' => 'watchman']);
    $scheduling = Scheduling::query()->create([
        'responsible_name' => 'Ana Silva',
        'responsible_email' => 'ana@example.com',
        'phone_number' => '11999999999',
        'motivation' => 'Visita agendada',
        'people_quantity' => 3,
        'scheduled_at' => '2026-10-25 10:30:00',
    ]);

    $this->actingAs($watchman);

    Livewire::test(ListSchedulings::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$scheduling])
        ->assertActionHidden('create')
        ->assertTableActionHidden('edit', $scheduling)
        ->assertTableBulkActionHidden('delete');
});

it('forbids watchmen from creating or editing schedulings directly', function () {
    $watchman = User::factory()->create(['role' => 'watchman']);
    $scheduling = Scheduling::query()->create([
        'responsible_name' => 'Ana Silva',
        'responsible_email' => 'ana@example.com',
        'phone_number' => '11999999999',
        'motivation' => 'Visita agendada',
        'people_quantity' => 3,
        'scheduled_at' => '2026-10-25 10:30:00',
    ]);

    $this->actingAs($watchman);

    Livewire::test(CreateScheduling::class)
        ->assertForbidden();

    Livewire::test(EditScheduling::class, ['record' => $scheduling->getKey()])
        ->assertForbidden();
});
