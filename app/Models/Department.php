<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'code',
        'status'
    ];

    public function employees()
    {
        return $this->hasMany(User::class, 'id');
    }
    public function subDepartments()
    {
        return $this->hasMany(SubDepartment::class);
    }
}
