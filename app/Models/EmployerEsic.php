<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployerEsic extends Model
{
    protected $table = 'employer_esic_settings';

    protected $fillable = [
        'esic_registration_number',
        'establishment_name',
        'establishment_address',
        'esic_branch_office',
        'employer_contribution',
        'employee_contribution',
        'esic_wage_limit',
        'esic_start_date',
    ];
}
