<?php

namespace App\Http\Controllers\User;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LeaveController extends \App\Http\Controllers\Controller
{
    public function submitRequest(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'leave_date' => 'required|date',
            'leave_time' => 'nullable|date_format:H:i',
            'reason' => 'nullable|string',
            'end_date' => 'nullable|date|after_or_equal:leave_date',
        ]);

        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return back()->with('error', 'Employee not found.');
        }

        $startDate = $request->leave_date;
        $endDate = $request->end_date ?? $startDate;

        // Check for overlapping leave
        $existingLeave = Leave::where('emp_id', $employee->id)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('leave_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('leave_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->first();

        if ($existingLeave) {
            return back()->with('info', 'You already have a leave request overlapping with the selected dates.');
        }

        // Create Leave entry
        $leave = new Leave();
        $leave->uid = $user->id;
        $leave->emp_id = $employee->id;
        $leave->type = $request->type;
        $leave->leave_date = $startDate;
        $leave->end_date = $endDate;
        $leave->leave_time = $request->leave_time ?? now()->format('H:i:s');
        $leave->status = 0;
        $leave->state = 0;

        try {
            $leave->save();

            // Create matching LeaveRequest entry
            LeaveRequest::create([
                'user_id'    => $user->id,
                'leave_type' => $request->type,
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'reason'     => $request->reason ?? 'N/A',
                'status'     => 0,
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withErrors(['error' => 'Failed to submit leave request: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Leave request submitted successfully.');
    }

    public function index()
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return back()->with('error', 'Employee not found.');
        }

        $leaves = Leave::where('emp_id', $employee->id)->get();

        return view('user.leave.index', compact('leaves'));
    }

    public function showForm()
    {
        return view('user.leave.request');
    }


public function attendance()
{
    $user = Auth::user();
    $employee = Employee::where('email', $user->email)->first();

    if (!$employee) {
        return back()->with('error', 'Employee not found.');
    }

    $today = Carbon::today();
    $startOfMonth = $today->copy()->startOfMonth();
    $dates = CarbonPeriod::create($startOfMonth, $today);

    $attendances = Attendance::where('emp_id', $employee->id)
        ->whereBetween('attendance_date', [$startOfMonth, $today])
        ->get()
        ->groupBy('attendance_date');

    $leaves = Leave::where('emp_id', $employee->id)
        ->where(function ($query) use ($startOfMonth, $today) {
            $query->whereBetween('leave_date', [$startOfMonth, $today])
                  ->orWhereBetween('end_date', [$startOfMonth, $today])
                  ->orWhere(function ($q) use ($startOfMonth, $today) {
                      $q->where('leave_date', '<=', $startOfMonth)
                        ->where('end_date', '>=', $today);
                  });
        })->get();

    $schedule = $employee->schedules->first();
    $scheduledTimeIn = $schedule ? Carbon::parse($schedule->time_in) : null;
    $scheduledTimeOut = $schedule ? Carbon::parse($schedule->time_out) : null;

    $records = [];

    foreach ($dates as $date) {
        $formattedDate = $date->format('Y-m-d');

        $dailyLeave = $leaves->first(function ($leave) use ($date) {
            $start = Carbon::parse($leave->leave_date);
            $end = $leave->end_date ? Carbon::parse($leave->end_date) : $start;
            return $date->between($start, $end);
        });

        $leaveStatus = $dailyLeave ? $dailyLeave->status : null; // 1=Approved, 0=Pending, 2=Rejected

        $checkIn = null;
        $checkOut = null;
        $status = 0; // Default to Absent

        if ($attendances->has($formattedDate)) {
            $attendance = $attendances[$formattedDate]->first();
            $checkInRaw = $attendance->attendance_time;
            $checkOutRaw = $attendance->checkout_time;

            $checkIn = $checkInRaw ? Carbon::parse($checkInRaw) : null;
            $checkOut = $checkOutRaw ? Carbon::parse($checkOutRaw) : null;

            $hoursWorked = $checkIn && $checkOut ? $checkIn->diffInHours($checkOut) : 0;

            if ($scheduledTimeIn) {
                $onTimeThreshold = $scheduledTimeIn->copy()->addMinutes(15);

                if ($checkIn->lte($onTimeThreshold)) {
                    $status = 1; // On Time
                } elseif ($hoursWorked >= 8) {
                    $status = 4; // Late but full hours
                } else {
                    $status = 3; // Late and not full hours
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

        $records[] = [
            'date' => $formattedDate,
            'scheduled_time_in' => $scheduledTimeIn ? $scheduledTimeIn->format('H:i:s') : null,
            'scheduled_time_out' => $scheduledTimeOut ? $scheduledTimeOut->format('H:i:s') : null,
            'check_in' => $checkIn ? $checkIn->format('H:i:s') : null,
            'check_out' => $checkOut ? $checkOut->format('H:i:s') : null,
            'hours_worked' => $checkIn && $checkOut ? $checkIn->diffInHours($checkOut) : 0,
            'status' => $status, // 0-Absent, 1-On Time, 2-Leave Approved, 3-Late, 4-Late But Full Time, 5-Pending Leave, 6-Rejected Leave
        ];
    }

    return view('user.leave.attendance', ['attendances' => collect($records)]);
}


}
