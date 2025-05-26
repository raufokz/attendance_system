<?php


// app/Helpers/AttendanceHelper.php
namespace App\Helpers;

class AttendanceHelper
{
    public static function calculateGrade(int $daysAttended): string
    {
        if ($daysAttended >= 26) {
            return 'A';
        } elseif ($daysAttended >= 20) {
            return 'B';
        } elseif ($daysAttended >= 15) {
            return 'C';
        } elseif ($daysAttended >= 10) {
            return 'D';
        } else {
            return 'F';
        }
    }
}

