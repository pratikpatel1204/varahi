<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualificationArea extends Model
{
    use HasFactory;

    protected $table = 'qualification_areas';

    protected $fillable = [
        'area_name',
        'status'
    ];
}
