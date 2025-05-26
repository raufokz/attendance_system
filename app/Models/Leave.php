<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
       protected $fillable = [
        'uid', 'emp_id', 'state', 'leave_time', 'leave_date', 'status', 'type', 'start_date', 'end_date'
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
    public function getTypeLabelAttribute()
{
    return match($this->type) {
        1 => 'Annual',
        2 => 'Sick',
        3 => 'Casual',
        default => 'Unknown',
    };
}

}
