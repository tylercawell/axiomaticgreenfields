<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        return inertia('Companies/Index', [
            'companies' => Company::query()
                ->withCount('Branches')
                ->latest()
                ->get(['id', 'name', 'created_at']),
            'can' => [
                'view' => $request->user()->can('view company'),
                'create' => $request->user()->can('create company'),
                'manage' => $request->user()->can('manage company'),
                'delete' => $request->user()->can('delete company'),
            ],  
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Company::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('companies');
    }
}
