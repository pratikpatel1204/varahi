<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends SpatieRole
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'guard_name',
    ];

    public function workingDays()
    {
        return $this->belongsToMany(
            WorkingDay::class,
            'designation_working_days',
            'designation_id',
            'working_day_id'
        );
    }
    
    public function leaveTypes()
    {
        return $this->belongsToMany(
            LeaveType::class,
            'designation_leave_types',
            'role_id',
            'leave_type_id'
        )->withPivot('total_days');
    }
}
