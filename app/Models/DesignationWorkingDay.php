<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignationWorkingDay extends Model
{
    protected $table = 'designation_working_days';

    protected $fillable = [
        'designation_id',
        'working_day_id'
    ];

    public $timestamps = true;

    public function workingDay()
    {
        return $this->belongsTo(WorkingDay::class, 'working_day_id');
    }

    public function designation()
    {
        return $this->belongsTo(Role::class, 'designation_id');
    }
}
