<?php

use App\Filament\Resources\EntryResource\Pages\EditEntry;
use App\Filament\Resources\EntryResource\Pages\ListEntries;
use App\Models\Entry;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('forbids managers from editing entries', function () {
    $manager = User::factory()->create(['role' => 'manager']);
    $entry = Entry::query()->create([
        'user_id' => $manager->id,
        'entry_time' => now(),
    ]);

    $this->actingAs($manager);

    Livewire::test(EditEntry::class, ['record' => $entry->getKey()])
        ->assertForbidden();
});

it('allows admins and watchmen to edit entries', function (string $role) {
    $user = User::factory()->create(['role' => $role]);
    $entry = Entry::query()->create([
        'user_id' => $user->id,
        'entry_time' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(EditEntry::class, ['record' => $entry->getKey()])
        ->assertOk();
})->with([
    'admins' => 'admin',
    'watchmen' => 'watchman',
]);

it('hides entry edit and exit actions from managers', function () {
    $manager = User::factory()->create(['role' => 'manager']);
    $entry = Entry::query()->create([
        'user_id' => $manager->id,
        'entry_time' => now(),
    ]);

    $this->actingAs($manager);

    Livewire::test(ListEntries::class)
        ->assertTableActionHidden('edit', $entry)
        ->assertTableActionHidden('registerExit', $entry);
});
