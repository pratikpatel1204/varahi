<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseReimbursement extends Model
{
    protected $table = 'expense_reimbursements';

    protected $fillable = [
        'employee_id',
        'reporting_manager',
        'entry_type',
        'year_id',
        'entry_month',
        'amount',
        'description',
        'bill',
        'status',
        'approved_by',
        'remarks',
        'created_by'
    ];

    // ✅ Cast JSON to array automatically
    protected $casts = [
        'bill' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Employee
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Reporting Manager
    public function reportingManager()
    {
        return $this->belongsTo(User::class, 'reporting_manager');
    }

    // Approved By
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Created By
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (Optional but useful)
    |--------------------------------------------------------------------------
    */

    // Pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Approved
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Rejected
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
