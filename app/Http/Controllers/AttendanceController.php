<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Latetime;
use App\Models\Employee;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AttendanceController extends Controller
{
public function index(Request $request)
{
    $query = Employee::with(['schedules', 'attendances' => function ($q) use ($request) {
        $q->orderBy('attendance_date', 'desc');

        if ($request->filled('start_date')) {
            $q->whereDate('attendance_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $q->whereDate('attendance_date', '<=', $request->end_date);
        }
    }]);

    if ($request->filled('emp_id')) {
        $query->where('id', $request->emp_id);
    }

    $employees = $query->get();
    $records = [];

    foreach ($employees as $employee) {
        $today = Carbon::today();
        $startOfMonth = $request->filled('start_date') ? Carbon::parse($request->start_date) : $today->copy()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : $today;
        $dates = CarbonPeriod::create($startOfMonth, $endDate);

        $attendances = $employee->attendances->groupBy('attendance_date');

        $leaves = Leave::where('emp_id', $employee->id)
            ->where(function ($query) use ($startOfMonth, $endDate) {
                $query->whereBetween('leave_date', [$startOfMonth, $endDate])
                      ->orWhereBetween('end_date', [$startOfMonth, $endDate])
                      ->orWhere(function ($q) use ($startOfMonth, $endDate) {
                          $q->where('leave_date', '<=', $startOfMonth)
                            ->where('end_date', '>=', $endDate);
                      });
            })->get();

        $schedule = $employee->schedules->first();
        $scheduledTimeIn = $schedule ? Carbon::parse($schedule->time_in) : null;
        $scheduledTimeOut = $schedule ? Carbon::parse($schedule->time_out) : null;

        foreach ($dates as $date) {
            $formattedDate = $date->format('Y-m-d');

            $checkIn = null;
            $checkOut = null;
            $hoursWorked = null; // ✅ Safe initialization
            $status = 0; // Default Absent

            $dailyLeave = $leaves->first(function ($leave) use ($date) {
                $start = Carbon::parse($leave->leave_date);
                $end = $leave->end_date ? Carbon::parse($leave->end_date) : $start;
                return $date->between($start, $end);
            });

            $leaveStatus = $dailyLeave ? $dailyLeave->status : null;

            if ($attendances->has($formattedDate)) {
                $attendance = $attendances[$formattedDate]->first();
                $checkIn = $attendance->attendance_time ? Carbon::parse($attendance->attendance_time) : null;
                $checkOut = $attendance->checkout_time ? Carbon::parse($attendance->checkout_time) : null;

                $hoursWorked = $checkIn && $checkOut ? $checkIn->diffInHours($checkOut) : null;

                if ($scheduledTimeIn) {
                    $onTimeThreshold = $scheduledTimeIn->copy()->addMinutes(15);

                    if ($checkIn->lte($onTimeThreshold)) {
                        $status = 1; // On Time
                    } elseif ($hoursWorked !== null && $hoursWorked >= 8) {
                        $status = 4; // Late but full hours
                    } else {
                        $status = 3; // Late
                    }
                } else {
                    $status = 1; // No schedule, assume on time
                }
            } elseif ($dailyLeave) {
                if ($leaveStatus == 1) {
                    $status = 2; // Approved Leave
                } elseif ($leaveStatus == 0) {
                    $status = 5; // Pending Leave
                } elseif ($leaveStatus == 2) {
                    $status = 6; // Rejected Leave
                }
            }

            if ($request->filled('status') && $status != $request->status) {
                continue;
            }

            $records[] = [
                'employee_name' => $employee->name,
                'emp_id' => $employee->id,
                'date' => $formattedDate,
                'scheduled_time_in' => $scheduledTimeIn ? $scheduledTimeIn->format('H:i:s') : null,
                'scheduled_time_out' => $scheduledTimeOut ? $scheduledTimeOut->format('H:i:s') : null,
                'check_in' => $checkIn ? $checkIn->format('H:i:s') : null,
                'check_out' => $checkOut ? $checkOut->format('H:i:s') : null,
                'hours_worked' => $hoursWorked,
                'status' => $status,
            ];
        }
    }

    $allEmployees = Employee::orderBy('name')->get();

    return view('admin.attendance', [
        'attendances' => collect($records),
        'employees' => $allEmployees
    ]);
}

    public function indexLatetime()
    {
        $latetimes = Latetime::with('employee.schedules')->get();
        return view('admin.latetime', compact('latetimes'));
    }

    public static function storeLateTime(Carbon $attendanceTime, Employee $employee)
    {
        $schedule = $employee->schedules->first();

        if (!$schedule || !$schedule->time_in) {
            return;
        }

        $scheduledTime = Carbon::parse($schedule->time_in);

        if ($attendanceTime->gt($scheduledTime)) {
            $difference = $attendanceTime->diff($scheduledTime)->format('%H:%I:%S');

            Latetime::create([
                'emp_id' => $employee->id,
                'duration' => $difference,
                'latetime_date' => $attendanceTime->toDateString(),
            ]);
        }
    }


}
