<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeProfile extends Model
{
    use HasFactory, SoftDeletes; 
    
    protected $table = 'employee_profiles';

    protected $fillable = [
        'employee_id',

        // Basic
        'joining_date',
        'experience_years',

        // Personal
        'passport_no',
        'passport_expiry',
        'blood_group_id',
        'father_name',

        // Aadhaar
        'aadhaar_no',
        'aadhaar_photo',

        // PAN
        'pan_no',
        'pan_photo',

        // Other
        'nationality',
        'religion',

        // Family
        'marital_status',
        'spouse_employment',
        'children',

        // Extra
        'differently_abled',
        'personal_email',
        // Emergency
        'emergency_name_1',
        'emergency_relation_1',
        'emergency_phone_1',
        'emergency_name_2',
        'emergency_relation_2',
        'emergency_phone_2',

        // About
        'about',

        // Job Info 
        'confirmation_date',
        'planned_join_date',
        'joined_on',
        'probation_period',
        'notice_period',
        'previous_experience',
        'total_experience',

        // Bank 

        'bank_name',
        'account_number',
        'ifsc_code',
        'branch',
        'account_holder_name',

        // PF
        'uan_number',
        'pf_account_number',
        'pf_applicable',
        'pf_join_date',
        'pf_exit_date',
        'pf_contribution_type',
        'pf_document',
        'pf_name',
        'pf_dob',
        'pf_previous_details',

        // ESI
        'esic_number',
        'esic_applicable',
        'esic_join_date',
        'esic_exit_date',
        'esic_name',
        'esic_dob',

        // Address
        'present_address',
        'present_city',
        'present_state',
        'present_country',
        'present_pincode',

        'permanent_address',
        'permanent_city',
        'permanent_state',
        'permanent_country',
        'permanent_pincode',
    ];

    protected $casts = [
        // Dates
        'joining_date'      => 'date',
        'passport_expiry'   => 'date',
        'confirmation_date' => 'date',
        'planned_join_date' => 'date',
        'joined_on'        => 'date',
        'pf_join_date'     => 'date',
        'pf_exit_date'     => 'date',
        'pf_dob'           => 'date',
        'esic_join_date'   => 'date',
        'esic_exit_date'   => 'date',
        'esic_dob'         => 'date',

        // Numbers
        'children'            => 'integer',
        'probation_period'    => 'integer',
        'notice_period'       => 'integer',
        'previous_experience' => 'float',
        'total_experience'    => 'float',
        
    ];


    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
