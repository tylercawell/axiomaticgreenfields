<?php

use App\Models\Branch;
use App\Models\CommissionNote;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use App\Services\CommissionNoteService;
use Database\Seeders\PermissionSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    
    $this->company = Company::factory()->create();
    $this->branch = Branch::factory()->create([
        'company_id' => $this->company->id
        ]);
    $this->employee = Employee::factory()->create([
        'branch_id' => $this->branch->id
        ]);
});

it('allows the original author to update their own commission note', function () {
    $author = User::factory()->create();
    $this->actingAs($author);

    $commissionNoteService = new CommissionNoteService();

    $commissionNote = $commissionNoteService->create([
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'amount' => 10000.00,
        'note' => 'Initial note',
    ], $author);

    $updatedNote = $commissionNoteService->update($commissionNote, [
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'amount' => 15000.00,
        'note' => 'Updated note',
    ], $author);

    expect($updatedNote->amount)->toEqual(15000.00);
    expect($updatedNote->note)->toEqual('Updated note');
});

it('forbids users without manage permission from updating others commission notes', function () {
    $author = User::factory()->create();
    $editor = User::factory()->create();
    $this->actingAs($editor);

    $commissionNoteService = new CommissionNoteService();

    $commissionNote = $commissionNoteService->create([
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'amount' => 10000.00,
        'note' => 'Initial note',
    ], $author);

    expect(fn () => 
        $commissionNoteService->update($commissionNote, [
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id,
            'employee_id' => $this->employee->id,
            'amount' => 15000.00,
            'note' => 'Updated note',
        ], $editor)
    )->toThrow(AuthorizationException::class);
});

it('allows users with manage permission to update any commission note', function () {
    $author = User::factory()->create();
    $editor = User::factory()->create();
    $editor->givePermissionTo('manage commission notes');
    $this->actingAs($editor);

    $commissionNoteService = new CommissionNoteService();

    $commissionNote = $commissionNoteService->create([
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'amount' => 10000.00,
        'note' => 'Initial note',
    ], $author);

    $updatedNote = $commissionNoteService->update($commissionNote, [
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'employee_id' => $this->employee->id,
        'amount' => 15000.00,
        'note' => 'Updated note by manager',
    ], $editor);

    expect($updatedNote->amount)->toEqual(15000.00);
    expect($updatedNote->note)->toEqual('Updated note by manager');
});