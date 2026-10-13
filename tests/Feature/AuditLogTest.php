<?php

use App\Models\AuditLog;
use App\Models\Car;
use App\Models\Entry;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('records create update and delete events with before and after values', function () {
    $actor = User::factory()->create(['role' => 'admin']);

    $this->actingAs($actor);

    $person = Person::query()->create([
        'user_id' => $actor->id,
        'name' => 'Original name',
    ]);

    $createdLog = AuditLog::query()
        ->where('auditable_type', Person::class)
        ->where('auditable_id', $person->id)
        ->where('event', 'created')
        ->firstOrFail();

    expect($createdLog->causer_id)->toBe($actor->id)
        ->and($createdLog->old_values)->toBeNull()
        ->and($createdLog->new_values)->toMatchArray([
            'user_id' => $actor->id,
            'name' => 'Original name',
        ]);

    $person->update(['name' => 'Updated name']);

    $updatedLog = AuditLog::query()
        ->where('auditable_type', Person::class)
        ->where('auditable_id', $person->id)
        ->where('event', 'updated')
        ->firstOrFail();

    expect($updatedLog->old_values)->toMatchArray(['name' => 'Original name'])
        ->and($updatedLog->new_values)->toMatchArray(['name' => 'Updated name']);

    $person->delete();

    $deletedLog = AuditLog::query()
        ->where('auditable_type', Person::class)
        ->where('auditable_id', $person->id)
        ->where('event', 'deleted')
        ->firstOrFail();

    expect($deletedLog->old_values)->toMatchArray(['name' => 'Updated name'])
        ->and($deletedLog->new_values)->toBeNull();
});

it('records changes for every business model and redacts credentials', function () {
    $actor = User::factory()->create(['role' => 'admin']);

    $this->actingAs($actor);

    $user = User::factory()->create([
        'password' => 'plain-text-password',
        'remember_token' => 'sensitive-token',
    ]);

    $person = Person::query()->create([
        'user_id' => $actor->id,
        'name' => 'Visitor',
    ]);

    $car = Car::query()->create([
        'user_id' => $actor->id,
        'plate' => 'ABC-1234',
        'color' => 'Preto',
    ]);

    $entry = Entry::query()->create([
        'user_id' => $actor->id,
        'entry_time' => now(),
    ]);

    foreach ([$user, $person, $car, $entry] as $model) {
        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => $model->getMorphClass(),
            'auditable_id' => $model->getKey(),
            'event' => 'created',
            'causer_id' => $actor->id,
        ]);
    }

    $userLog = AuditLog::query()
        ->where('auditable_type', User::class)
        ->where('auditable_id', $user->id)
        ->where('event', 'created')
        ->firstOrFail();

    expect($userLog->new_values['password'])->toBe('[REDACTED]')
        ->and($userLog->new_values['remember_token'])->toBe('[REDACTED]');
});
