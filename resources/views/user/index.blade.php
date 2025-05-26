@php
    use Carbon\Carbon;
    $todayAttendance = $recentAttendance->first();
    $currentTime = now();
@endphp

@extends('layouts.master')

@section('css')
<style>
:root {
    --primary: #4361ee;
    --primary-light: #ebf0ff;
    --success: #3a7a10;
    --info: #219ebc;
    --warning: #f4a261;
    --danger: #ef233c;
    --dark: #2b2d42;
    --light: #f8f9fa;
}

/* Key Animation */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.03); }
    100% { transform: scale(1); }
}

/* Base Card Design */
.dashboard-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    background: white;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.12);
}

/* Attendance Widget - Glass Morphism */
.attendance-widget {
    background: linear-gradient(135deg, rgba(67,97,238,0.9) 0%, rgba(101,76,237,0.9) 100%);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    position: relative;
    overflow: hidden;
}

.attendance-widget::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
    border-radius: 50%;
}

/* Stat Cards */
.stat-card {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    font-size: 24px;
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    margin: 8px 0;
    font-family: 'Inter', sans-serif;
}

.stat-title {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.8;
    margin-bottom: 4px;
}

/* Progress Ring */
.progress-ring {
    position: relative;
    width: 160px;
    height: 160px;
}

.progress-ring circle {
    fill: none;
    stroke-linecap: round;
    transform: rotate(-90deg);
    transform-origin: 50% 50%;
}

.progress-ring-bg {
    stroke: rgba(255,255,255,0.2);
    stroke-width: 8;
}

.progress-ring-fill {
    stroke: white;
    stroke-width: 8;
    /* stroke-dasharray will be set inline in the HTML */
    transition: stroke-dasharray 0.6s ease;
}

/* Action Button */
.action-btn {
    border: none;
    border-radius: 50px;
    padding: 14px 28px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.action-btn-primary {
    background: white;
    color: var(--primary);
    box-shadow: 0 4px 12px rgba(67,97,238,0.25);
}

.action-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(67,97,238,0.3);
}

.action-btn-outline {
    background: transparent;
    color: white;
    border: 2px solid rgba(255,255,255,0.3);
}

.action-btn-outline:hover {
    background: rgba(255,255,255,0.1);
    border-color: white;
}

/* Time Display */
.time-display {
    font-family: 'JetBrains Mono', monospace;
    font-size: 18px;
    background: rgba(0,0,0,0.1);
    padding: 6px 12px;
    border-radius: 6px;
    display: inline-block;
}

/* List Group Items */
.list-group-item {
    transition: all 0.2s ease;
}

.list-group-item:hover {
    transform: translateX(4px);
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 50px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .attendance-content {
        flex-direction: column;
    }
    .progress-ring {
        margin-bottom: 24px;
    }
    .stat-value {
        font-size: 28px;
    }
    .action-btn {
        padding: 12px 20px;
        font-size: 14px;
    }
}

/* Modal Styles */
.modal-content {
    border-radius: 16px;
    overflow: hidden;
}

.modal-header {
    background: var(--primary);
    color: white;
}

/* Table Styles */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table thead th {
    background: var(--light);
    border-top: none;
}

.table td, .table th {
    vertical-align: middle;
}

/* Search Input */
#attendanceSearch {
    border-radius: 50px;
    padding: 10px 20px;
    border: 1px solid #dee2e6;
}
</style>
@endsection

@section('content')
<div class="container-fluid">

    <!-- Greeting Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Good {{ $currentTime->hour < 12 ? 'Morning' : ($currentTime->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ explode(' ', $user->name)[0] }}</h2>
                    <p class="text-muted mb-0">{{ $currentTime->format('l, F j, Y') }}</p>
                </div>
                <div class="time-display">
                    <i class="ti-time mr-2"></i>{{ $currentTime->format('h:i A') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Attendance Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-card attendance-widget p-4">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <!-- Left Content -->
                    <div class="mb-4 mb-md-0">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stat-icon mr-3" style="background: rgba(255,255,255,0.2)">
                                <i class="ti-timer"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">Today's Attendance</h4>
                                <p class="mb-0 opacity-75">
                                    {{ \Carbon\Carbon::today()->format('F j, Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="mr-4">
                                <p class="small opacity-75 mb-1">Check-in</p>
                                <p class="h5 mb-0 font-weight-bold">
                                    {{ $todayAttendance && $todayAttendance->attendance_time !== null ? Carbon::parse($todayAttendance->attendance_time)->format('h:i A') : '--:--' }}
                                </p>
                            </div>
                            <div>
                                <p class="small opacity-75 mb-1">Check-out</p>
                                <p class="h5 mb-0 font-weight-bold">
                                    @if($todayAttendance && $todayAttendance->checkout_time)
                                        {{ Carbon::parse($todayAttendance->checkout_time)->format('h:i A') }}
                                    @else
                                        --:--
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Ring -->
                    <div class="progress-ring mx-4">
                        <svg viewBox="0 0 36 36">
                            <circle class="progress-ring-bg" cx="18" cy="18" r="15.9155"/>
                            <circle class="progress-ring-fill" cx="18" cy="18" r="15.9155" style="stroke-dasharray: {{ $workedPercentage }}, 100;"/>
                            <text x="18" y="20.5" fill="white" font-size="4" text-anchor="middle" font-weight="bold">
                                <tspan id="timerText" x="18" dy="0">--:--:--</tspan>
                                <tspan x="18" dy="1.5em" font-size="2" opacity="0.8">Hours Worked</tspan>
                            </text>
                        </svg>
                    </div>

                    <!-- Action Button -->
                    <div class="text-center mt-3 mt-md-0">
                        @if($todayAttendance && $todayAttendance->attendance_date == Carbon::today()->toDateString())
                            @if(!$todayAttendance->checkout_time)
                                <form method="POST" action="{{ route('user.attendance.mark') }}">
                                    @csrf
                                    <button type="submit" class="action-btn action-btn-primary" id="checkoutBtn">
                                        <i class="ti-shift-right mr-2"></i> Check Out
                                    </button>
                                </form>
                                <p class="small mt-2 opacity-75">
                                    Working since {{ $todayAttendance->attendance_time !== null ? Carbon::parse($todayAttendance->attendance_time)->format('h:i A') : '' }}
                                </p>
                            @else
                                <button class="action-btn action-btn-outline" disabled>
                                    <i class="ti-check mr-2"></i> Completed
                                </button>
                                <p class="small mt-2 opacity-75">
                                    You worked {{ Carbon::parse($todayAttendance->attendance_time)->diffInHours($todayAttendance->checkout_time) }}h today
                                </p>
                            @endif
                        @else
                            <form method="POST" action="{{ route('user.attendance.mark') }}">
                                @csrf
                                <button type="submit" class="action-btn action-btn-primary">
                                    <i class="ti-shift-left mr-2"></i> Check In
                                </button>
                            </form>
                            <p class="small mt-2 opacity-75">
                                Ready to start your day?
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row">
        <!-- Days Attended -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card h-100 p-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--primary-light); color: var(--primary);">
                        <i class="ti-calendar"></i>
                    </div>
                    <h5 class="stat-title">Days Attended</h5>
                    <h2 class="stat-value text-dark">{{ $daysAttended }}</h2>
                </div>
            </div>
        </div>

        <!-- Attendance Grade -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card h-100 p-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(58,122,16,0.1); color: var(--success);">
                        <i class="ti-medall"></i>
                    </div>
                    <h5 class="stat-title">Attendance Grade</h5>
                    <h2 class="stat-value text-dark">{{ $grade }}</h2>
                    <div class="progress mt-2" style="height: 6px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved Leaves -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card h-100 p-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(33,158,188,0.1); color: var(--info);">
                        <i class="ti-check-box"></i>
                    </div>
                    <h5 class="stat-title">Approved Leaves</h5>
                    <h2 class="stat-value text-dark">{{ $leaveApproved }}</h2>
                    <p class="text-muted mb-0 small">{{ $leaveApproved }} days this year</p>
                </div>
            </div>
        </div>

        <!-- Pending Leaves -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card h-100 p-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(244,162,97,0.1); color: var(--warning);">
                        <i class="ti-time"></i>
                    </div>
                    <h5 class="stat-title">Pending Leaves</h5>
                    <h2 class="stat-value text-dark">{{ $leavePending}}</h2>
                    <p class="text-muted mb-0 small">{{ $leavePending }} days requested</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row mt-4">
        <!-- Recent Attendance -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0">Recent Attendance</h5>
                    <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#attendanceModal">
                        View All
                    </button>
                </div>
                <div class="card-body">
                    @if(empty($last5DaysSummary))
                        <div class="text-center py-4">
                            <i class="ti-calendar text-muted" style="font-size: 40px;"></i>
                            <p class="mt-2 text-muted">No attendance records found</p>
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($last5DaysSummary as $day)
                                <li class="list-group-item d-flex justify-content-between align-items-center"
  title="Date: {{ \Carbon\Carbon::parse($day['date'])->format('d M Y') }}">
                                {{ \Carbon\Carbon::parse($day['date'])->format('d M Y') }}
                                    @php
                                        $statusClass = '';
                                        switch ($day['status']) {
                                            case 'Present': $statusClass = 'badge-success'; break;
                                            case 'Late': $statusClass = 'badge-warning'; break;
                                            case 'Leave': $statusClass = 'badge-primary'; break;
                                            case 'Absent': $statusClass = 'badge-danger'; break;
                                            default: $statusClass = 'badge-secondary'; break;
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $day['status'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Leave Requests -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="mb-0">Recent Leave Requests</h5>
                    <a href="{{ route('user.leave.index') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recentLeaveRequests->isEmpty())
                        <div class="text-center py-4">
                            <i class="ti-agenda text-muted" style="font-size: 40px;"></i>
                            <p class="mt-2 text-muted">No leave requests found</p>
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($recentLeaveRequests as $leave)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="font-weight-bold">
                                            {{ \Carbon\Carbon::parse($leave->leave_date)->format('d M') }} -
                                            {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                        </span>
                                        <small class="d-block text-muted">
                                            {{ $leave->leave_type }} •
                                            {{ \Carbon\Carbon::parse($leave->created_at)->diffForHumans() }}
                                        </small>
                                    </div>
                                    <span class="badge badge-pill
                                        @if($leave->status == 1) badge-success
                                        @elseif($leave->status == 0) badge-warning
                                        @else badge-danger @endif">
                                        {{ $leave->status == 1 ? 'Approved' : ($leave->status == 0 ? 'Pending' : 'Rejected') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: All Attendance -->
<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Attendance Records</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <!-- Search input -->
        <div class="mb-3">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="ti-search"></i></span>
            </div>
            <input type="text" id="attendanceSearch" class="form-control" placeholder="Search attendance records...">
          </div>
        </div>

        <!-- Detailed Attendance Table -->
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="thead-light">
              <tr>
                <th>Date</th>
                <th>Scheduled In</th>
                <th>Scheduled Out</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Hours Worked</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="attendanceList">
              @foreach($detailedRecords as $record)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($record['date'])->format('d M Y') }}</td>
                  <td>{{ $record['scheduled_time_in'] ?? '-' }}</td>
                  <td>{{ $record['scheduled_time_out'] ?? '-' }}</td>
                  <td>{{ $record['check_in'] ?? '-' }}</td>
                  <td>{{ $record['check_out'] ?? '-' }}</td>
                  <td>{{ $record['hours_worked'] ?? '-' }}</td>
                  <td>
                    @php
                      $statusBadge = '';
                      switch($record['status']) {
                        case 0: $statusBadge = '<span class="badge bg-danger">Absent</span>'; break;
                        case 1: $statusBadge = '<span class="badge bg-success">On Time</span>'; break;
                        case 2: $statusBadge = '<span class="badge bg-primary">Leave Approved</span>'; break;
                        case 3: $statusBadge = '<span class="badge bg-warning text-dark">Late</span>'; break;
                        case 4: $statusBadge = '<span class="badge bg-info text-dark">Late but Full Time</span>'; break;
                        case 5: $statusBadge = '<span class="badge bg-secondary">Pending Leave</span>'; break;
                        case 6: $statusBadge = '<span class="badge bg-dark">Rejected Leave</span>'; break;
                        default: $statusBadge = '<span class="badge bg-light text-dark">Unknown</span>';
                      }
                    @endphp
                    {!! $statusBadge !!}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Button pulse animation
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.style.animation = 'pulse 2s infinite';

        checkoutBtn.addEventListener('mouseenter', () => {
            checkoutBtn.style.animation = 'none';
        });

        checkoutBtn.addEventListener('mouseleave', () => {
            checkoutBtn.style.animation = 'pulse 2s infinite';
        });
    }

    // Live timer functionality
    const timerText = document.getElementById('timerText');
    const checkInTimeString = @json($todayAttendance->attendance_time ?? null ? \Carbon\Carbon::parse($todayAttendance->attendance_time)->toIso8601String() : null);
    const checkOutTimeString = @json($todayAttendance->checkout_time ?? null  ? \Carbon\Carbon::parse($todayAttendance->checkout_time)->toIso8601String() : null);

    if (checkInTimeString) {
        const checkInTime = new Date(checkInTimeString);
        const checkOutTime = checkOutTimeString ? new Date(checkOutTimeString) : null;

        function formatTime(seconds) {
            const h = Math.floor(seconds / 3600).toString().padStart(2, '0');
            const m = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
            const s = Math.floor(seconds % 60).toString().padStart(2, '0');
            return `${h}:${m}:${s}`;
        }

        function updateTimer() {
            const now = new Date();
            const endTime = checkOutTime || now;
            let duration = Math.floor((endTime - checkInTime) / 1000);
            if (duration < 0) duration = 0;
            timerText.textContent = formatTime(duration);
        }

        updateTimer();

        // If user has NOT checked out, keep updating every second
        if (!checkOutTime) {
            setInterval(updateTimer, 1000);
        }
    }

    // Attendance search functionality
    const attendanceSearch = document.getElementById('attendanceSearch');
    if (attendanceSearch) {
        attendanceSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#attendanceList tr');

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchTerm) ? '' : 'none';
            });
        });
    }
});

</script>
@endsection
