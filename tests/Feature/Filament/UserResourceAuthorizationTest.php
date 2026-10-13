<?php

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('hides the users menu from watchmen', function () {
    $watchman = User::factory()->create(['role' => 'watchman']);

    $this->actingAs($watchman);

    expect(UserResource::shouldRegisterNavigation())->toBeFalse();
});

it('keeps the users menu visible to admins', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin);

    expect(UserResource::shouldRegisterNavigation())->toBeTrue();
});

it('forbids watchmen from opening user creation', function () {
    $watchman = User::factory()->create(['role' => 'watchman']);

    $this->actingAs($watchman);

    Livewire::test(CreateUser::class)
        ->assertForbidden();
});

it('allows admins to open user creation', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin);

    Livewire::test(CreateUser::class)
        ->assertOk();
});
