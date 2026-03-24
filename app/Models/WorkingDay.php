<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingDay extends Model
{
    protected $table = 'working_days';

    protected $fillable = [
        'day_name',
        'is_working'
    ];
    public function designations()
    {
        return $this->belongsToMany(
            Role::class,
            'designation_working_days',
            'working_day_id',
            'designation_id'
        );
    }
}
