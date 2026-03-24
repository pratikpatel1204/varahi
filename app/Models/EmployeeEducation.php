<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    protected $table = 'employee_educations';
    
    protected $fillable = [

        'employee_id',
        'qualification',
        'institution_name',
        'course',
        'start_date',
        'end_date',
        'qualification_area',
        'grade',
        'remark',
        'document',

    ];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }
}
