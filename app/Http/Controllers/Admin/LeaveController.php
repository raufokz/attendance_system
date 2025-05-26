<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\LeaveRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    // Show all leave requests
public function index(Request $request)
{
    $query = Leave::with('employee')->orderBy('created_at', 'desc');

    // Filter by employee ID (or name)
    if ($request->filled('employee_id')) {
        $query->where('emp_id', $request->employee_id);
    }

    // Filter by start date (leave_date >= start_date)
    if ($request->filled('start_date')) {
        $query->whereDate('leave_date', '>=', $request->start_date);
    }

    // Filter by end date (leave_date <= end_date)
    if ($request->filled('end_date')) {
        $query->whereDate('leave_date', '<=', $request->end_date);
    }

    $leaves = $query->get();

    // Also pass list of employees for the filter dropdown
    $employees = \App\Models\Employee::orderBy('name')->get();

    return view('admin.leaves.index', compact('leaves', 'employees'));
}

    // Show a single leave request detail
    public function show($id)
    {
        $leave = Leave::with('employee')->findOrFail($id);
        return view('admin.leaves.show', compact('leave'));  // Make sure view exists
    }
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

    // Check if leave already exists for this day
    $existingLeave = Leave::where('emp_id', $employee->id)
        ->where('leave_date', $request->leave_date)
        ->first();

    if ($existingLeave) {
        return back()->with('info', 'Leave already requested for this date.');
    }

    // Create Leave entry
    $leave = new Leave();
    $leave->uid = $user->id;
    $leave->emp_id = $employee->id;
    $leave->type = $request->type;
    $leave->leave_date = $request->leave_date;
    $leave->leave_time = $request->leave_time ?? now()->format('H:i:s');
    $leave->status = 0;
    $leave->state = 0;

    try {
        $leave->save();

        // Create matching LeaveRequest entry
        LeaveRequest::create([
            'user_id'    => $user->id,
            'leave_type' => $request->type,
            'start_date' => $request->leave_date,
            'end_date'   => $request->end_date ?? $request->leave_date,
            'reason'     => $request->reason ?? 'N/A',
            'status'     => 0,
        ]);

    } catch (\Illuminate\Database\QueryException $e) {
        return back()->withErrors(['error' => 'Failed to submit leave request: ' . $e->getMessage()]);
    }

    return back()->with('success', 'Leave request submitted successfully.');
}

    // Approve a leave request
public function approve($id)
{
    $leave = Leave::findOrFail($id);
    $leave->status = 1;
    $leave->state = 1;
    $leave->save();

    // Update corresponding record in leave_requests
    LeaveRequest::where('user_id', $leave->uid)
        ->where('start_date', $leave->leave_date)
        ->update(['status' => 1]);

    return redirect()->back()->with('success', 'Leave approved.');
}

    // Reject a leave request
public function reject($id)
{
    $leave = Leave::findOrFail($id);
    $leave->status = 2;
    $leave->state = 2;
    $leave->save();

    // Update corresponding record in leave_requests
    LeaveRequest::where('user_id', $leave->uid)
        ->where('start_date', $leave->leave_date)
        ->update(['status' => 2]);

    return redirect()->back()->with('error', 'Leave rejected.');
}

}


