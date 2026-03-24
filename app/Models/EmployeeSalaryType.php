<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryType extends Model
{
    protected $table = 'employee_salary_types';

    protected $fillable = [
        'employee_id',
        'year',
        'salary_type_name',
        'salary_value',
        'salary_value_type',
        'salary_type',
        'amount'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function salaryType()
    {
        return $this->belongsTo(SalaryType::class);
    }
}