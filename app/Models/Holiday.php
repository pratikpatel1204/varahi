<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [

        'year_id',
        'title',
        'from_date',
        'to_date',
        'type',
        'status'
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }
}
