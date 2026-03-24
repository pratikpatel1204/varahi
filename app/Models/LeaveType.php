<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $fillable = [
        'name',
        'status'
    ];

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,'designation_leave_types','leave_type_id','role_id'
        )->withPivot('total_days');
    }
}
