<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAsset extends Model
{
    protected $table = 'employee_assets';

    protected $fillable = [
        'employee_id',
        'asset_id',
        'assigned_by',
        'status',
        'assigned_on',
        'return_date'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
