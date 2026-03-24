<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryYear extends Model
{
    use HasFactory;

    /**
     * Table Name
     */
    protected $table = 'employee_salary_years';

    /**
     * Mass Assignable Fields
     */
    protected $fillable = [
        'employee_id',
        'year',
        'salary_basis',
        'payment_type',
        'gross_Salary',
    ];

    /**
     * ======================
     * RELATIONSHIPS
     * ======================
     */

    /**
     * Employee relation
     */
    public function employee()
    {
        return $this->belongsTo(user::class);
    }

    /**
     * Year relation
     */
    public function year()
    {
        return $this->belongsTo(Year::class);
    }
}
