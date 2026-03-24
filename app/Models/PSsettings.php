<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PfDetail extends Model
{
    protected $table = 'pf_settings';

    protected $fillable = [
        'pf_registration_number',
        'establishment_name',
        'establishment_address',
        'pf_office_region',
        'employer_contribution',
        'employee_contribution',
        'eps_contribution',
        'epf_contribution',
        'pf_wage_ceiling',
        'pf_joining_date'
    ];

    protected $casts = [
        'pf_joining_date' => 'date',
        'employer_contribution' => 'decimal:2',
        'employee_contribution' => 'decimal:2',
        'eps_contribution' => 'decimal:2',
        'epf_contribution' => 'decimal:2',
    ];
}
