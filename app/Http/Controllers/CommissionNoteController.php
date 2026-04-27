<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Employee;
use App\Models\CommissionNote;

use App\Services\CommissionNoteService;
use App\Http\Requests\CommissionNotes\StoreCommissionNoteRequest;
use App\Http\Requests\CommissionNotes\UpdateCommissionNoteRequest;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CommissionNoteController extends Controller
{
    public function index(Request $request)
{
    $companyId = $request->integer('company_id');
    $branchId = $request->integer('branch_id');

    $companies = Company::select('id', 'name')->get();

    $branches = Branch::select('id', 'name', 'company_id')
        ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
        ->get();

    $employees = Employee::select('id', 'branch_id', 'first_name', 'last_name')
        ->when($branchId, fn ($q) => $q->where('branch_id', $branchId))
        ->get()
        ->map(fn ($e) => [
            'id' => $e->id,
            'branch_id' => $e->branch_id,
            'full_name' => "{$e->first_name} {$e->last_name}",
        ]);

    $commissionNotes = CommissionNote::with(['employee', 'author'])
        ->get()
        ->map(fn ($note) => [
            'id' => $note->id,
            'company_id' => $note->company_id,
            'branch_id' => $note->branch_id,
            'employee_id' => $note->employee_id,
            'author_id' => $note->author_id,
            'amount' => $note->amount,
            'note' => $note->note,
            'created_at' => $note->created_at->format('Y-m-d H:i'),
            'employee' => [
                'id' => $note->employee->id,
                'full_name' => "{$note->employee->first_name} {$note->employee->last_name}",
            ],
            'author' => [
                'id' => $note->author->id,
                'name' => $note->author->name,
            ],
        ]);

    return inertia('CommissionNotes/Index', [
        'companies' => $companies,
        'branches' => $branches,
        'employees' => $employees,
        'commissionNotes' => $commissionNotes,
        'filters' => [
            'company_id' => $companyId,
            'branch_id' => $branchId,
        ],
        'can' => [
            'view' => $request->user()->can('view commission notes'),
            'manage' => $request->user()->can('manage commission notes'),
        ],
    ]);
}

    public function store(StoreCommissionNoteRequest $request, CommissionNoteService $commissionNoteService): RedirectResponse 
    {
        $commissionNoteService->create(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('commissionNotes', [
                'company_id' => $request->integer('company_id'),
                'branch_id' => $request->integer('branch_id'),
            ])
            ->with('success', 'Commission note created successfully.');
    }

   public function update(
        UpdateCommissionNoteRequest $request,
        CommissionNote $commissionNote,
        CommissionNoteService $commissionNoteService
    ): RedirectResponse {
        $commissionNoteService->update(
            $commissionNote,
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('commissionNotes', [
                'company_id' => $request->integer('company_id'),
                'branch_id' => $request->integer('branch_id'),
            ])
            ->with('success', 'Commission note updated successfully.');
    }       
}