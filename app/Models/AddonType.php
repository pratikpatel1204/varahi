<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddonType extends Model
{
    use HasFactory;

    protected $table = 'addon_types';

    protected $fillable = [
        'name',
        'status'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employeeAddons()
    {
        return $this->hasMany(EmployeeAddon::class, 'addon_type_id');
    }
}
