<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'uid','emp_id','state', 'attendance_time', 'checkout_time', 'attendance_date', 'status', 'type', 'remarks', 'location',
    ];

    // Attendance belongs to a User (employee)
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }
    public function user()
{
    return $this->belongsTo(User::class, 'uid');

}
public function employee()
{
    return $this->belongsTo(Employee::class, 'emp_id');
}

}
