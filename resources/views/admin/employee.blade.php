@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Employees</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
            <li class="breadcrumb-item active">Employees</li>
        </ol>
    </div>
@endsection

@section('button')
    <a href="#addEmployee" data-toggle="modal" class="btn btn-success btn-sm btn-flat">
        <i class="mdi mdi-plus mr-2"></i>Add New Employee
    </a>
@endsection

@section('content')
    @include('includes.flash')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="datatable-buttons" class="table table-hover table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
       <thead class="thead-dark">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Position</th>
        <th>Email</th>
        <th>Schedule</th>
        <th>Approved</th> <!-- NEW -->
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    @foreach ($employees as $employee)
        <tr>
            <td>{{ $employee->id }}</td>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->position }}</td>
            <td>{{ $employee->email }}</td>
            <td>{{ optional($employee->schedules->first())->slug }}</td>
            <td>
                @if ($employee->user && $employee->user->is_approved)
                    <span class="badge badge-success">Yes</span>
                @else
                    <span class="badge badge-danger">No</span>
                @endif
            </td>
            <td>
                <a href="#editEmployee{{ $employee->id }}" data-toggle="modal" class="btn btn-success btn-sm"><i class='fa fa-edit'></i></a>
                <a href="#deleteEmployee{{ $employee->id }}" data-toggle="modal" class="btn btn-danger btn-sm"><i class='fa fa-trash'></i></a>
            </td>
        </tr>
    @endforeach
</tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Include modals --}}
    @include('includes.add_employee')
    @foreach ($employees as $employee)
        @include('includes.edit_delete_employee', ['employee' => $employee])
    @endforeach
@endsection

@section('script')
    <!-- Responsive-table-->
    <script src="{{ URL::asset('plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>

    <script>
        $(function() {
            $('.table-responsive').responsiveTable({
                addDisplayAllBtn: 'btn btn-secondary'
            });
        });
    </script>
@endsection
