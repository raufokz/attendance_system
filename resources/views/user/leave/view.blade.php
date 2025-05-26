@extends('layouts.master')

@section('content')
<h2>View All Leave Requests</h2>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Employee</th>
            <th>Type</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($leaves as $leave)
            <tr>
                <td>{{ $leave->id }}</td>
                <td>{{ $leave->employee->name ?? 'N/A' }}</td>
                <td>{{ ['1' => 'Annual', '2' => 'Sick', '3' => 'Casual'][$leave->type] ?? 'Unknown' }}</td>
                <td>{{ $leave->leave_date }}</td>
                <td>
                    @if($leave->status == 0)
                        Pending
                    @elseif($leave->status == 1)
                        Approved
                    @else
                        Rejected
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection




<!--
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Leave Details</h2>

    <table class="table">
        <tr>
            <th>Date</th>
            <td>{{ \Carbon\Carbon::parse($leave->leave_date)->format('d M Y') }}</td>
        </tr>
        <tr>
            <th>Type</th>
            <td>{{ ucfirst($leave->type) }}</td>
        </tr>
        <tr>
            <th>Time</th>
            <td>{{ $leave->leave_time }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if($leave->status == 0)
                    Pending
                @elseif($leave->status == 1)
                    Approved
                @else
                    Rejected
                @endif
            </td>
        </tr>
    </table>
</div>
@endsection -->
