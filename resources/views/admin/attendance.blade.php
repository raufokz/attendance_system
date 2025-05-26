@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Attendance Management</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Attendance</a></li>
        </ol>
    </div>
@endsection

@section('content')
@include('includes.flash')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <!-- Filter Form -->
<!-- Filter Form Positioned Near Table Buttons -->
<div style="position: absolute; left: 11%; width:-webkit-fill-available; z-index: 99; display: none;" class="d-none d-sm-block">
    <form method="GET" action="{{ route('admin.attendance.index') }}" class="form-inline d-flex flex-wrap align-items-center gap-2">
        <div class="form-group mx-2">
            <label for="emp_id" class="mr-1">Employee</label>
            <select name="emp_id" id="emp_id" class="form-control form-select select-underline">
                <option value="">All</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('emp_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mx-2">
            <label for="start_date" class="mr-1">Start</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control input-underline">
        </div>

        <div class="form-group mx-2">
            <label for="end_date" class="mr-1">End</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"  class="form-control input-underline">
        </div>

        <div class="form-group mx-2">
            <button type="submit" class="btn btn-primary mr-1">Filter</button>
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>


                <!-- Attendance Table -->
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table class="table table-hover table-striped table-bordered dt-responsive nowrap" style="width: 100%;">
                            <thead class="thead-dark">
                                <tr>
                                    <th data-priority="1">Date</th>
                                    <th data-priority="2">Employee</th>
                                    <th data-priority="3">Attendance Time</th>
                                    <th data-priority="4">Check out</th>
                                    <th data-priority="5">Hours Worked</th>
                                    <th data-priority="6">Status</th>
                                </tr>
                            </thead>
                           <tbody>
    @forelse($attendances as $attendance)
        <tr>
            <td>{{ \Carbon\Carbon::parse($attendance['date'])->format('d M Y') }}</td>
            <td>{{ $attendance['employee_name'] ?? 'N/A' }}</td>
            <td>
                {{ $attendance['check_in'] ? \Carbon\Carbon::createFromFormat('H:i:s', $attendance['check_in'])->format('g:i A') : '-' }}
            </td>
            <td>
                {{ $attendance['check_out'] ? \Carbon\Carbon::createFromFormat('H:i:s', $attendance['check_out'])->format('g:i A') : '-' }}
            </td>
      <td>
                {{ $attendance['hours_worked'] !== null ? $attendance['hours_worked'] . ' hrs' : '-' }}
            </td>
            <td>
                @switch($attendance['status'])
                    @case(0)
                        <span class="badge badge-danger badge-pill">Absent</span>
                        @break
                    @case(1)
                        <span class="badge badge-success badge-pill">On Time</span>
                        @break
                    @case(2)
                        <span class="badge badge-info badge-pill">Approved Leave</span>
                        @break
                    @case(3)
                        <span class="badge badge-warning badge-pill">Late</span>
                        @break
                    @case(4)
                        <span class="badge badge-primary badge-pill">Late (8+ hrs)</span>
                        @break
                    @case(5)
                        <span class="badge badge-warning badge-pill">Pending Leave</span>
                        @break
                    @case(6)
                        <span class="badge badge-danger badge-pill">Rejected Leave</span>
                        @break
                    @default
                        <span class="badge badge-secondary badge-pill">Unknown</span>
                @endswitch
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">No attendance records found.</td>
        </tr>
    @endforelse
</tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('script')
    <!-- Responsive-table -->
    <script src="{{ URL::asset('plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>
    <script>
        $(function() {
            $('.table-responsive').responsiveTable({
                addDisplayAllBtn: 'btn btn-secondary'
            });
        });



    </script>
@endsection
