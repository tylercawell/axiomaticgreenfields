<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionNote extends Model
{
    /** @use HasFactory<\Database\Factories\CommissionNoteFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'branch_id',
        'employee_id',
        'author_id',
        'amount',
        'note',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
