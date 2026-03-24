<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeExperience extends Model
{
    protected $fillable = [
        'employee_id',
        'company',
        'designation',
        'start_date',
        'end_date',
        'is_present'
    ];


    public function employee()
    {
        return $this->belongsTo(User::class);
    }
}
