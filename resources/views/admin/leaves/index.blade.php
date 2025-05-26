@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Leave Management</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">Leave</a></li>
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
<div style="position: absolute; left: 11%; width:-webkit-fill-available; z-index: 99; display: none;" class="d-none d-sm-block">
    <form method="GET" action="{{ route('admin.leave.index') }}" class="form-inline d-flex flex-wrap align-items-center gap-2">
        <div class="form-group mx-2">
            <label for="employee_id" class="mr-1">Employee</label>
            <select name="employee_id" id="employee_id" class="form-control form-select select-underline">
                <option value="">-- All Employees --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
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
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="form-control input-underline">
        </div>

        <div class="form-group mx-2">
            <button type="submit" class="btn btn-primary mr-1">Filter</button>
            <a href="{{ route('admin.leave.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
</div>


                <!-- Table -->
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="datatable-buttons" class="table table-hover table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="thead-dark">
                                <tr>
                                    <th data-priority="1">ID</th>
                                    <th data-priority="2">Employee</th>
                                    <th data-priority="3">Type</th>
                                    <th data-priority="4">Start Date</th>
                                    <th data-priority="5">End Date</th>
                                    <th data-priority="6">Status</th>
                                    <th data-priority="7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $leave)
                                    <tr>
                                        <td>{{ $leave->id }}</td>
                                        <td>{{ optional($leave->employee)->name ?? 'N/A' }}</td>
                                        <td>{{ ['1' => 'Annual', '2' => 'Sick', '3' => 'Casual'][$leave->type] ?? 'Unknown' }}</td>
                                        <td>{{ $leave->leave_date }}</td>
                                        <td>{{ $leave->end_date ?? 'N/A' }}</td>
                                        <td>
                                            @if($leave->status == 0)
                                                <span class="badge badge-warning badge-pill">Pending</span>
                                            @elseif($leave->status == 1)
                                                <span class="badge badge-success badge-pill">Approved</span>
                                            @else
                                                <span class="badge badge-danger badge-pill">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.leave.approve', $leave->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.leave.reject', $leave->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
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
