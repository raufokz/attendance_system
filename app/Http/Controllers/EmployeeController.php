<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Schedule;
use App\Http\Requests\EmployeeRec;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees with schedules.
     */
    public function index()
    {
        return view('admin.employee')->with([
            'employees' => Employee::all(),
            'schedules' => Schedule::all()
        ]);
    }

    /**
     * Store a newly created employee and corresponding user.
     */
public function store(EmployeeRec $request)
{
    $request->validated();

    // Create employee
    $employee = new Employee;
    $employee->name = $request->name;
    $employee->position = $request->position;
    $employee->email = $request->email;
    $employee->pin_code = Hash::make($request->pin_code);
    $employee->save();

    // Attach schedule if provided
    if ($request->schedule) {
        $schedule = Schedule::whereSlug($request->schedule)->first();
        if ($schedule) {
            $employee->schedules()->attach($schedule);
        }
    }

    // Create corresponding user
    $user = new User;
    $user->name = $employee->name;
    $user->email = $employee->email;
    $user->password = Hash::make($request->pin_code);
    $user->pin_code = $request->pin_code;
    $user->permissions = json_encode([]);
    $user->is_approved = 1;
    $user->save();

    return redirect()->route('employees.index')->with('success', 'Employee and User created successfully!');
}

    /**
     * Update the specified employee and schedule.
     */
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'email' => 'required|email|unique:employees,email,' . $id,
        'pin_code' => 'nullable|string|min:4',
        'schedule' => 'required|string',
    ]);

    $employee = Employee::findOrFail($id);

    // Update employee
    $employee->name = $request->name;
    $employee->position = $request->position;
    $employee->email = $request->email;
    if ($request->filled('pin_code')) {
        $employee->pin_code = bcrypt($request->pin_code);
    }
    $employee->save();

    // Update schedule
    $schedule = Schedule::whereSlug($request->schedule)->first();
    if ($schedule) {
        $employee->schedules()->sync([$schedule->id]);
    } else {
        $employee->schedules()->detach();
    }

    // Update user
    $user = User::where('email', $employee->email)->first(); // Adjust if needed
    if ($user) {
        $user->name = $employee->name;
        $user->email = $employee->email;
        if ($request->filled('pin_code')) {
            $user->password = Hash::make($request->pin_code);
            $user->pin_code = $request->pin_code;
        }
        $user->is_approved = $request->has('is_approved') ? 1 : 0;
        $user->save();
    }

    return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
}


    /**
     * Remove the specified employee.
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
