<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $fillable = [
        'year',
        'status'
    ];
    public function employeeSalaryYears()
{
    return $this->hasMany(EmployeeSalaryYear::class);
}

}
