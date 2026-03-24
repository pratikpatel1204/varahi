<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyWorkingDay extends Model
{
    use HasFactory;

    // Table name explicitly, agar Laravel convention follow nahi kar raha
    protected $table = 'monthly_working_days';

    // Fillable fields
    protected $fillable = [
        'month_id',
        'year_id',
        'total_days',
    ];

    // Optional: timestamps already enabled
    public $timestamps = true;

    // ===== Relations =====
    
    // Month relation (assuming you have a Month model)
    public function month()
    {
        return $this->belongsTo(Month::class, 'month_id', 'id');
    }
}
