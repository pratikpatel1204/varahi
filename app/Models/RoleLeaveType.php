<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RoleLeaveType extends Model
{
    protected $table = 'designation_leave_types';

    protected $fillable = [
        'role_id',
        'leave_type_id',
        'total_days'
    ];

    public $timestamps = true;

    // Role Relationship
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Leave Type Relationship
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
}
