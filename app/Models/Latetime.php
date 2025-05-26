<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Latetime extends Model
{
    protected $fillable = ['emp_id', 'duration', 'latetime_date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
}
