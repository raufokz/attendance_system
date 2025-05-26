<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Latetime;

class CheckController extends Controller
{
    public function index()
    {
        return view('admin.check')->with(['employees' => Employee::all()]);
    }

    /**
     * Admin Manual Check Entry
     */
    public function CheckStore(Request $request)
    {
        $user = Auth::user();

        // Attendance processing
        if (isset($request->attd)) {
            foreach ($request->attd as $date => $employees) {
                foreach ($employees as $empId => $value) {
                    $employee = Employee::with('schedules')->find($empId);
                    if (!$employee || !$employee->schedules->first()) continue;

                    $schedule = $employee->schedules->first();
                    $scheduledTime = Carbon::parse($schedule->time_in);

                    $existing = Attendance::where('attendance_date', $date)
                        ->where('emp_id', $empId)
                        ->first();

                    if (!$existing) {
                        $now = Carbon::now();
                        $attendanceTime = $now->format('H:i:s');
                        $isLate = $now->gt($scheduledTime);

                        // Save attendance
                        $attendance = new Attendance();
                        $attendance->uid = $user->id ?? null;
                        $attendance->emp_id = $empId;
                        $attendance->attendance_time = $attendanceTime;
                        $attendance->attendance_date = $date;
                        $attendance->status = $isLate ? 1 : 0;
                        $attendance->save();

                        // Late time logging
                        if ($isLate) {
                            $difference = $now->diff($scheduledTime)->format('%H:%I:%S');
                            Latetime::create([
                                'emp_id' => $empId,
                                'duration' => $difference,
                                'latetime_date' => $date,
                            ]);
                        }
                    }
                }
            }
        }

        // Leave processing
        if (isset($request->leave)) {
            foreach ($request->leave as $date => $employees) {
                foreach ($employees as $empId => $value) {
                    $employee = Employee::with('schedules')->find($empId);
                    if (!$employee || !$employee->schedules->first()) continue;

                    $schedule = $employee->schedules->first();

                    $existing = Leave::where('leave_date', $date)
                        ->where('emp_id', $empId)
                        ->first();

                    if (!$existing) {
                        $leave = new Leave();
                        $leave->emp_id = $empId;
                        $leave->leave_time = $schedule->time_out;
                        $leave->leave_date = $date;
                        $leave->status = 1;
                        $leave->save();
                    }
                }
            }
        }

        flash()->success('Success', 'You have successfully submitted the attendance!');
        return back();
    }

    public function sheetReport()
    {
        return view('admin.sheet-report')->with(['employees' => Employee::all()]);
    }

    /**
     * Self Attendance Marking
     */
    public function markAttendance(Request $request)
    {
        $user = Auth::user();
        $employee = Employee::where('email', $user->email)->with('schedules')->first();

        if (!$employee || !$employee->schedules->first()) {
            return back()->with('error', 'Employee or schedule not found.');
        }

        $schedule = $employee->schedules->first();
        $scheduledTime = Carbon::parse($schedule->time_in);
        $now = Carbon::now();

        $existing = Attendance::where('emp_id', $employee->id)
            ->where('attendance_date', $now->toDateString())
            ->first();

        if ($existing) {
            return back()->with('info', 'Attendance already marked for today.');
        }

        $isLate = $now->gt($scheduledTime);
        $status = $isLate ? 1 : 0;

        // Save attendance
        $attendance = new Attendance();
        $attendance->uid = $user->id;
        $attendance->emp_id = $employee->id;
        $attendance->attendance_date = $now->toDateString();
        $attendance->attendance_time = $now->toTimeString();
        $attendance->status = $status;
        $attendance->save();

        // Save late time if applicable
        if ($isLate) {
            $difference = $now->diff($scheduledTime)->format('%H:%I:%S');
            Latetime::create([
                'emp_id' => $employee->id,
                'duration' => $difference,
                'latetime_date' => $now->toDateString(),
            ]);
        }

        return back()->with('success', 'Attendance marked successfully.');
    }
}
