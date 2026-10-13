<?php

use App\Filament\Resources\EntryResource\Pages\CreateEntry;
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

it('assigns new entries to the logged-in watchman', function () {
    $watchman = User::factory()->create(['role' => 'watchman']);
    $anotherUser = User::factory()->create(['role' => 'admin']);

    $this->actingAs($watchman);

    Livewire::test(CreateEntry::class)
        ->assertFormSet(['user_id' => $watchman->id])
        ->assertFormFieldIsDisabled('user_id')
        ->fillForm(['user_id' => $anotherUser->id])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('entries', ['user_id' => $watchman->id]);
    $this->assertDatabaseMissing('entries', ['user_id' => $anotherUser->id]);
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
