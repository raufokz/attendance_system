@extends('layouts.master')

@section('css')
    <!-- Optional: Add table styles if used in admin layout -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">Leave</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">My Leave Requests</li>
        </ol>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-4">My Leave Requests</h4>

                @if($leaves->isEmpty())
                    <div class="alert alert-info">No leave records found.</div>
                @else
                    @php
                        $leaveTypes = [
                            1 => 'Annual',
                            2 => 'Sick',
                            3 => 'Casual',
                        ];
                    @endphp

                    <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table class="table table-striped table-bordered mb-0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaves as $leave)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($leave->leave_date)->format('d M Y') }}</td>
                                            <td>{{ $leaveTypes[$leave->type] ?? 'Unknown' }}</td>
                                            <td>{{ $leave->leave_time }}</td>
                                            <td>
                                                @if($leave->status == 0)
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif($leave->status == 1)
                                                    <span class="badge badge-success">Approved</span>
                                                @else
                                                    <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <!-- Optional: Include script if responsive table plugin used in your admin layout -->
    <script src="{{ URL::asset('plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>
    <script>
        $(function() {
            $('.table-responsive').responsiveTable({
                addDisplayAllBtn: 'btn btn-secondary'
            });
        });
    </script>
@endsection
