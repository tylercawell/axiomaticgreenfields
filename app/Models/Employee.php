<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'branch_id',
        'first_name',
        'last_name',
        'employee_number',
        'email',
        'phone_number'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    
    public function full_name(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // public function getEmailAttribute($value): string
    // {
    //     return Crypt::decryptString($value);
    // }

    // public function getPhoneNumberAttribute($value): string
    // {
    //     return Crypt::decryptString($value);
    // }

}
