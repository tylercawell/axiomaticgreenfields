<?php

use App\Models\User;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Employee;
use App\Models\CommissionNote;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);

    $this->company = Company::factory()->create();

    $this->branch = Branch::factory()->create([
        'company_id' => $this->company->id,
    ]);

    $this->employee = Employee::factory()->create([
        'branch_id' => $this->branch->id,
        'company_id' => $this->company->id,
    ]);
});

it('allows a user with manage permission to create a commission note', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('view commission notes');
    $user->givePermissionTo('manage commission notes');

    $this->actingAs($user);

    $response = $this->post(route('commissionNotes.store'), [
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'amount' => 10000.00,
        'note' => 'Initial commission note',
    ]);

    $response->assertRedirect(route('commissionNotes', [
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
    ]));

    $this->assertDatabaseHas('commission_notes', [
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'amount' => 10000.00,
        'note' => 'Initial commission note',
    ]);
});

it('forbids users without manage permission from creating commission notes', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('view commission notes');

    $this->actingAs($user);

    $response = $this->post(route('commissionNotes.store'), [
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'amount' => 10000.00,
        'note' => 'Initial commission note',
    ]);

    $response->assertForbidden();

    $this->assertDatabaseMissing('commission_notes', [
        'note' => 'Initial commission note',
    ]);
});

it('allows the original author to update their own commission note', function () {
    $author = User::factory()->create();
    $author->givePermissionTo('view commission notes');

    $this->actingAs($author);

    $commissionNote = CommissionNote::factory()->create([
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'author_id' => $author->id,
        'amount' => 10000.00,
        'note' => 'Initial note',
    ]);

    $response = $this->put(route('commissionNotes.update', $commissionNote), [
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'amount' => 15000.00,
        'note' => 'Updated by Author',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('commission_notes', [
        'id' => $commissionNote->id,
        'amount' => 15000.00,
        'note' => 'Updated by Author',
    ]);
});

it('forbids a non author without manage permission from updating a commission note', function () {
    $author = User::factory()->create();

    $editor = User::factory()->create();
    $editor->givePermissionTo('view commission notes');

    $this->actingAs($editor);

    $commissionNote = CommissionNote::factory()->create([
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'author_id' => $author->id,
        'amount' => 10000.00,
        'note' => 'Initial note',
    ]);

    $response = $this->put(route('commissionNotes.update', $commissionNote), [
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'amount' => 15000.00,
        'note' => 'Attempted update by non-author without manage permission',
    ]);

    $response->assertForbidden();

    $this->assertDatabaseHas('commission_notes', [
        'id' => $commissionNote->id,
        'amount' => 10000.00,
        'note' => 'Initial note',
    ]);
});

it('allows users with manage permission to update any commission note', function () {
    $author = User::factory()->create();

    $editor = User::factory()->create();
    $editor->givePermissionTo('view commission notes');
    $editor->givePermissionTo('manage commission notes');

    $this->actingAs($editor);

    $commissionNote = CommissionNote::factory()->create([
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'author_id' => $author->id,
        'amount' => 10000.00,
        'note' => 'Initial note',
    ]);

    $response = $this->put(route('commissionNotes.update', $commissionNote), [
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'amount' => 15000.00,
        'note' => 'Updated by Manager',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('commission_notes', [
        'id' => $commissionNote->id,
        'amount' => 15000.00,
        'note' => 'Updated by Manager',
    ]);
});