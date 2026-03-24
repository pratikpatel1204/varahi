<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignationLeaveType extends Model
{
    protected $table = 'designation_leave_types';

    protected $fillable = [
        'role_id',
        'leave_type_id',
        'total_days',
    ];

    public function designation()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
}
