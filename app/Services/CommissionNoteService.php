<?php

namespace App\Services;

use DB;
use Auth;

use App\Models\User;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Employee;
use App\Models\CommissionNote;

use App\Jobs\CommissionNotes\AllocationNotification;
use App\Jobs\CommissionNotes\AllcoationEmailNotification;

use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class CommissionNoteService
{
    public function create(array $data, User $user): CommissionNote
    {
        $branchBelongsToCompany = Branch::query()
            ->whereKey($data['branch_id'])
            ->where('company_id', $data['company_id'])
            ->exists();

        if (! $branchBelongsToCompany) {
            throw ValidationException::withMessages([
                'branch_id' => 'The selected branch does not belong to the selected company.',
            ]);
        }

        $employeeBelongsToBranch = Employee::query()
            ->whereKey($data['employee_id'])
            ->where('branch_id', $data['branch_id'])
            ->exists();

        if (! $employeeBelongsToBranch) {
            throw ValidationException::withMessages([
                'employee_id' => 'The selected employee does not belong to the selected branch.',
            ]);
        }

        return CommissionNote::query()->create([
            'company_id' => $data['company_id'],
            'branch_id' => $data['branch_id'],
            'employee_id' => $data['employee_id'],
            'author_id' => $user->id,
            'amount' => $data['amount'],
            'note' => $data['note'] ?? null,
        ]);

        // // Dispatch notification job
        // $employee = Employee::find($data['employee_id']);
        // $amount = $data['amount'];
        // $note = $data['note'] ?? null;

        // AllocationNotification::dispatch($employee, $amount, $note);
        // AllocationEmailNotification::dispatch($employee, $amount, $note);

    }

    public function update(CommissionNote $commissionNote, array $data, User $user): CommissionNote
    {

        if (
            $commissionNote->author_id !== auth()->id() && ! $user->can('manage commission notes')
        ) {
            throw new AuthorizationException('You do not have permission to edit this commission note.');
        }

        $branchBelongsToCompany = Branch::query()
            ->whereKey($data['branch_id'])
            ->where('company_id', $data['company_id'])
            ->exists();

        if (! $branchBelongsToCompany) {
            throw ValidationException::withMessages([
                'branch_id' => 'The selected branch does not belong to the selected company.',
            ]);
        }

        $employeeBelongsToBranch = Employee::query()
            ->whereKey($data['employee_id'])
            ->where('branch_id', $data['branch_id'])
            ->exists();

        if (! $employeeBelongsToBranch) {
            throw ValidationException::withMessages([
                'employee_id' => 'The selected employee does not belong to the selected branch.',
            ]);
        }

        return DB::transaction(function () use ($commissionNote, $data) {
            $commissionNote->update([
                'company_id' => $data['company_id'],
                'branch_id' => $data['branch_id'],
                'employee_id' => $data['employee_id'],
                'amount' => $data['amount'],
                'note' => $data['note'] ?? null,
            ]);

            return $commissionNote;
        });
    }
}