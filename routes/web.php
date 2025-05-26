<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BiometricDeviceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;               // <-- Make sure this points to correct namespace
use App\Http\Controllers\Admin\LeaveController as AdminLeaveController;  // Explicit import of admin LeaveController
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\User\LeaveController as UserLeaveController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CheckController;
use App\Jobs\ClearAttendanceJob;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Public routes
Route::get('attended/{user_id}', [AttendanceController::class, 'attended'])->name('attended');
Route::get('attended-before/{user_id}', [AttendanceController::class, 'attendedBefore'])->name('attendedBefore');

// Authentication routes
Auth::routes(['register' => true, 'reset' => true]);

// Routes for authenticated users with 'user' role
Route::group([
    'middleware' => ['auth', 'Role'],
    'roles' => ['user'],
    'prefix' => 'user',
    'as' => 'user.'
], function () {

    Route::get('/', [UserDashboardController::class, 'index'])->name('index');
    Route::post('/attendance/mark', [UserDashboardController::class, 'markAttendance'])->name('attendance.mark');
    Route::get('/attendance/view', [AttendanceController::class, 'viewAttendance'])->name('attendance.view');
    Route::get('/leave/request', [UserLeaveController::class, 'showForm'])->name('leave.request');
    Route::post('/leave/request', [UserLeaveController::class, 'submitRequest'])->name('leave.submit');
Route::get('/leave/index', [UserLeaveController::class, 'index'])->name('leave.index');
  Route::post('leave/view', [UserLeaveController::class, 'viewLeave'])->name('leave.view');       // View leave by date
    Route::get('leave/attendance', [UserLeaveController::class, 'attendance'])->name('leave.attendance'); // View attendance
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::patch('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture');
});


// Routes for authenticated users with 'admin' role
Route::group(['middleware' => ['auth', 'Role'], 'roles' => ['admin']], function () {

    Route::resource('/employees', EmployeeController::class);

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
    Route::get('/latetime', [AttendanceController::class, 'indexLatetime'])->name('indexLatetime');
    Route::get('/overtime', [LeaveController::class, 'indexOvertime'])->name('indexOvertime');

    // Use the Admin LeaveController (with namespace Admin)
    Route::get('/leave', [AdminLeaveController::class, 'index'])->name('admin.leave.index');
    Route::get('/leave/{id}', [AdminLeaveController::class, 'show'])->name('admin.leave.show');
    Route::post('/leave/{id}/approve', [AdminLeaveController::class, 'approve'])->name('admin.leave.approve');
    Route::post('/leave/{id}/reject', [AdminLeaveController::class, 'reject'])->name('admin.leave.reject');

    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
Route::post('/leave/{id}/approve', [AdminLeaveController::class, 'approve'])->name('admin.leave.approve');
Route::get('/admin/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('admin.attendance.index');

    Route::resource('/schedule', ScheduleController::class);

    Route::get('/check', [CheckController::class, 'index'])->name('check');
    Route::get('/sheet-report', [CheckController::class, 'sheetReport'])->name('sheet-report');
    Route::post('/check-store', [CheckController::class, 'checkStore'])->name('check_store');

    // Biometric Fingerprint Devices
    Route::resource('/finger_device', BiometricDeviceController::class);
    Route::delete('finger_device/destroy', [BiometricDeviceController::class, 'massDestroy'])->name('finger_device.massDestroy');
    Route::get('finger_device/{fingerDevice}/employees/add', [BiometricDeviceController::class, 'addEmployee'])->name('finger_device.add.employee');
    Route::get('finger_device/{fingerDevice}/get/attendance', [BiometricDeviceController::class, 'getAttendance'])->name('finger_device.get.attendance');

    // Temp Clear Attendance route
    // Route::get('finger_device/clear/attendance', function () {
    //     $midnight = \Carbon\Carbon::createFromTime(23, 50, 0);
    //     $diff = now()->diffInMinutes($midnight);
    //     dispatch(new ClearAttendanceJob())->delay(now()->addMinutes($diff));
    //     toast("Attendance Clearance Queue will run at 11:50 P.M!", "success");
    //     return back();
    // })->name('finger_device.clear.attendance');
});

// Profile routes
Route::middleware('auth')->group(function () {
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::patch('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture');

});
Route::get('/not-approved', function () {
    return view('auth.not-approved');
})->name('not-approved');

// Home route after login
Route::get('/home', [HomeController::class, 'index'])->name('home');
