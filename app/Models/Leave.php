<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'from_date',
        'to_date',
        'days',
        'remaining_days',
        'reason',
        'reporting_manager_id',
        'status',
        'approved_by',
        'comment',
    ];

    // Employee
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Leave Type
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }

    // Reporting Manager
    public function manager()
    {
        return $this->belongsTo(User::class, 'reporting_manager_id');
    }

    // Approved By
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function scopeManagerScope($query)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['admin', 'super admin'])) {
            return $query->where('reporting_manager_id', $user->id);
        }
        return $query;
    }
}
