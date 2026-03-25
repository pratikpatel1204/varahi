<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanManagement extends Model
{
    protected $table = 'loan_managements';
    protected $fillable = [
        'employee_id',
        'reporting_manager',
        'loan_amount',
        'interest_rate',
        'loan_amount_with_interest',
        'no_of_emi',
        'emi_start_date',
        'emi_end_date',
        'emi_amount',
        'status',
        'approved_by',
        'remarks'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function reportingManager()
    {
        return $this->belongsTo(User::class, 'reporting_manager');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
