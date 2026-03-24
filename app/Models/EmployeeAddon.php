<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAddon extends Model
{
    use HasFactory;

    protected $table = 'employee_addons';

    protected $fillable = [
        'employee_id',
        'addon_type_id',
        'year_id',
        'month_id',
        'amount'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function addonType()
    {
        return $this->belongsTo(AddonType::class, 'addon_type_id');
    }
}
