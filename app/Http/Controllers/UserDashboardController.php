<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Latetime;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Helpers\AttendanceHelper;

class UserDashboardController extends Controller
{
public function index()
{
    $user = Auth::user();

    $employee = Employee::where('email', $user->email)->with('schedules')->first();
    if (!$employee) {
        return back()->with('error', 'Employee not found.');
    }

    $empId = $employee->id;
    $today = Carbon::now()->toDateString();
    $schedule = $employee->schedules->first();

    // Basic counts
    $daysAttended = Attendance::where('emp_id', $empId)->distinct('attendance_date')->count('attendance_date');
    $totalAttendance = Attendance::where('emp_id', $empId)->count();
    $grade = AttendanceHelper::calculateGrade($daysAttended);
    $recentAttendance = Attendance::where('emp_id', $empId)->orderBy('attendance_date', 'desc')->take(5)->get();
    $allAttendance = Attendance::where('emp_id', $empId)->orderBy('attendance_date', 'desc')->get();

    // Leaves this month
    $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
    $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
    $leavesThisMonth = Leave::where('emp_id', $empId)
        ->where('status', 1)
        ->whereBetween('leave_date', [$startOfMonth, $endOfMonth])
        ->get()
        ->keyBy('leave_date');

    $leaveApproved = $leavesThisMonth->count();
    $leavePending = Leave::where('emp_id', $empId)->where('status', 0)->count();
    $leaveRejected = Leave::where('emp_id', $empId)->where('status', 2)->count();

    $recentLeaveRequests = Leave::where('emp_id', $empId)->orderBy('created_at', 'desc')->take(5)->get();

    // Attendance summary per day (for whole month)
    $attendanceSummary = [];
    $currentMonthDays = Carbon::now()->daysInMonth;

    for ($day = 1; $day <= $currentMonthDays; $day++) {
        $date = Carbon::now()->startOfMonth()->addDays($day - 1)->toDateString();

        // Skip future dates without approved leave
        if ($date > $today) {
            $leaveFuture = Leave::where('emp_id', $empId)
                ->where('status', 1)
                ->where('leave_date', $date)
                ->first();

            if (!$leaveFuture) {
                continue;
            }
        }

        $status = 'Absent';

        $attendance = Attendance::where('emp_id', $empId)->where('attendance_date', $date)->first();

        if (isset($leavesThisMonth[$date])) {
            $status = 'Leave';
        } elseif ($attendance) {
            $checkIn = Carbon::parse($attendance->attendance_time);

            if ($schedule && $schedule->time_in) {
                $scheduledIn = Carbon::parse($schedule->time_in);

                $lateThreshold = $scheduledIn->copy()->addMinutes(15);
                if ($checkIn->lte($lateThreshold)) {
                    $status = 'Present';
                } else {
                    $status = 'Late';
                }
            } else {
                $status = 'Present';
            }
        }

        $attendanceSummary[$date] = [
            'date' => $date,
            'status' => $status,
        ];
    }

// Last 5 days attendance summary
$last5DaysDates = [];
for ($i = 4; $i >= 0; $i--) {
    $last5DaysDates[] = Carbon::now()->subDays($i)->toDateString();
}

$last5DaysSummary = [];

foreach ($last5DaysDates as $date) {
    if ($date > $today) {
        $leaveFuture = Leave::where('emp_id', $empId)
            ->where('status', 1)
            ->whereDate('leave_date', $date)
            ->first();
        if (!$leaveFuture) {
            continue;
        }
    }

    $status = 'Absent';

    // Check leave with different statuses on this date
    $leave = Leave::where('emp_id', $empId)
        ->whereDate('leave_date', $date)
        ->first();

    if ($leave) {
        if ($leave->status == 2) {
            $status = 'Rejected Leave';
        } elseif ($leave->status == 1) {
            $status = 'Leave';
        } elseif ($leave->status == 0) {
            $status = 'Pending';
        }
    } else {
        $attendance = Attendance::where('emp_id', $empId)->where('attendance_date', $date)->first();

        if ($attendance) {
            $checkIn = Carbon::parse($attendance->attendance_time);

            if ($schedule && $schedule->time_in) {
                $scheduledIn = Carbon::parse($schedule->time_in);
                $lateThreshold = $scheduledIn->copy()->addMinutes(15);
                if ($checkIn->lte($lateThreshold)) {
                    $status = 'Present';
                } else {
                    $status = 'Late';
                }
            } else {
                $status = 'Present';
            }
        }
    }

    $last5DaysSummary[] = [
        'date' => $date,
        'status' => $status,
    ];
}

    // Today's worked % and duration
    $scheduledDurationSeconds = 0;
    $workedDurationSeconds = 0;

    if ($schedule && $schedule->time_in && $schedule->time_out) {
        $scheduledTimeIn = Carbon::parse($schedule->time_in);
        $scheduledTimeOut = Carbon::parse($schedule->time_out);
        $scheduledDurationSeconds = $scheduledTimeOut->diffInSeconds($scheduledTimeIn);

        $attendance = Attendance::where('emp_id', $empId)->where('attendance_date', $today)->first();

        if ($attendance && $attendance->attendance_time) {
            $checkIn = Carbon::parse($attendance->attendance_time);
            $checkOut = $attendance->checkout_time ? Carbon::parse($attendance->checkout_time) : Carbon::now();

            $workedDurationSeconds = $checkOut->diffInSeconds($checkIn);
        }
    }

    $workedPercentage = $scheduledDurationSeconds > 0
        ? min(100, ($workedDurationSeconds / $scheduledDurationSeconds) * 100)
        : 0;

    $workedDurationFormatted = gmdate('H:i', $workedDurationSeconds);

    // ==== Add detailed attendance records from attendance() here ====

    $todayCarbon = Carbon::today();
    $startOfMonthCarbon = $todayCarbon->copy()->startOfMonth();
    $dates = CarbonPeriod::create($startOfMonthCarbon, $todayCarbon);

    $attendances = Attendance::where('emp_id', $empId)
        ->whereBetween('attendance_date', [$startOfMonthCarbon, $todayCarbon])
        ->get()
        ->groupBy('attendance_date');

    $leaves = Leave::where('emp_id', $empId)
        ->where(function ($query) use ($startOfMonthCarbon, $todayCarbon) {
            $query->whereBetween('leave_date', [$startOfMonthCarbon, $todayCarbon])
                  ->orWhereBetween('end_date', [$startOfMonthCarbon, $todayCarbon])
                  ->orWhere(function ($q) use ($startOfMonthCarbon, $todayCarbon) {
                      $q->where('leave_date', '<=', $startOfMonthCarbon)
                        ->where('end_date', '>=', $todayCarbon);
                  });
        })->get();

    $scheduledTimeIn = $schedule ? Carbon::parse($schedule->time_in) : null;
    $scheduledTimeOut = $schedule ? Carbon::parse($schedule->time_out) : null;

    $detailedRecords = [];

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

        $detailedRecords[] = [
            'date' => $formattedDate,
            'scheduled_time_in' => $scheduledTimeIn ? $scheduledTimeIn->format('H:i:s') : null,
            'scheduled_time_out' => $scheduledTimeOut ? $scheduledTimeOut->format('H:i:s') : null,
            'check_in' => $checkIn ? $checkIn->format('H:i:s') : null,
            'check_out' => $checkOut ? $checkOut->format('H:i:s') : null,
            'hours_worked' => $checkIn && $checkOut ? $checkIn->diffInHours($checkOut) : 0,
            'status' => $status, // 0-Absent, 1-On Time, 2-Leave Approved, 3-Late, 4-Late But Full Time, 5-Pending Leave, 6-Rejected Leave
        ];
    }

    // Pass detailed records as 'detailedAttendance'
    return view('user.index', compact(
        'user',
        'daysAttended',
        'totalAttendance',
        'recentAttendance',
        'allAttendance',
        'recentLeaveRequests',
        'leaveApproved',
        'leaveRejected',
        'leavePending',
        'workedPercentage',
        'workedDurationFormatted',
        'grade',
        'attendanceSummary',
        'last5DaysSummary',
        'detailedRecords'  // new detailed attendance data for index view
    ));
}


public function markAttendance(Request $request)
{
    $user = Auth::user();
    $employee = Employee::where('email', $user->email)->with('schedules')->first();

    if (!$employee) {
        return back()->with('error', 'Employee not found.');
    }

    $schedule = $employee->schedules->first();

    if (!$schedule || !$schedule->time_in || !$schedule->time_out) {
        return back()->with('error', 'No valid schedule time_in or time_out found.');
    }

    $now = Carbon::now();
    $today = $now->toDateString();

    // Check if employee has approved (1) or pending (0) leave today
    $leaveExists = Leave::where('emp_id', $employee->id)
        ->whereDate('leave_date', $today)  // Adjust if leave can span multiple days
        ->whereIn('status', [0, 1])        // 0 = pending, 1 = approved
        ->exists();

    if ($leaveExists) {
        return back()->with('error', 'You cannot mark attendance on a day when leave is pending or approved.');
    }

    // Parse scheduled time_in and time_out
    $scheduledTimeIn = Carbon::parse($schedule->time_in);
    $scheduledTimeOut = Carbon::parse($schedule->time_out);

    // Find today's attendance record for this employee
    $attendance = Attendance::where('emp_id', $employee->id)
        ->where('attendance_date', $today)
        ->first();

    if (!$attendance) {
        // No attendance record yet, so this is check-in
        $isLate = $now->gt($scheduledTimeIn->copy()->addMinutes(15)); // 15 minutes late threshold
        $status = $isLate ? 0 : 1; // 0 = late, 1 = on time

        $attendance = new Attendance();
        $attendance->uid = $user->id;
        $attendance->emp_id = $employee->id;
        $attendance->attendance_date = $today;
        $attendance->attendance_time = $now->toTimeString();
        $attendance->status = $status;
        $attendance->save();

        return back()->with('success', 'Check-in marked successfully.');
    } else {
        // Attendance record exists, so this might be check-out
        if ($attendance->checkout_time) {
            return back()->with('error', 'You have already checked out for today.');
        }

        if ($now->lt($scheduledTimeIn)) {
            return back()->with('error', 'Cannot check out before scheduled time in.');
        }

        $attendance->checkout_time = $now->toTimeString();

        // Optionally update status or other info here
        $attendance->save();

        return back()->with('success', 'Check-out marked successfully.');
    }
}



}
