@extends('layouts.master')

@section('css')
<link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet">
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Attendance Management</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Attendance</a></li>
    </ol>
</div>
@endsection

@section('content')
@include('includes.flash')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table class="table table-bordered table-striped table-hover dt-responsive nowrap" style="width: 100%;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Scheduled Time In</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    use Carbon\Carbon;
                                    $today = Carbon::today();
                                @endphp

                                @forelse($attendances as $record)
                                    @php
                                        $recordDate = Carbon::parse($record['date']);
                                        $isFuture = $recordDate->greaterThan($today);

                                        $statusLabel = 'Unknown';
                                        $statusClass = 'badge-secondary';

                                        if ($isFuture) {
                                            $statusLabel = 'Not Available';
                                            $statusClass = 'badge-secondary';
                                        } else {
                 switch ($record['status']) {
    case 0:
        $statusLabel = 'Absent';
        $statusClass = 'badge-danger';
        break;
    case 1:
        $statusLabel = 'Present (On Time)';
        $statusClass = 'badge-success';
        break;
    case 2:
        $statusLabel = 'Leave (Approved)';
        $statusClass = 'badge-info';
        break;
    case 3:
        $statusLabel = 'Late (Not Full Hours)';
        $statusClass = 'badge-warning';
        break;
    case 4:
        $statusLabel = 'Late (Full Hours)';
        $statusClass = 'badge-warning';
        break;
    case 5:
        $statusLabel = 'Leave (Pending)';
        $statusClass = 'badge-secondary';
        break;
    case 6:
        $statusLabel = 'Leave (Rejected)';
        $statusClass = 'badge-danger';
        break;
}


                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $recordDate->format('d M Y') }}</td>

                                        <td>
                                            {{ isset($record['scheduled_time_in']) && $record['scheduled_time_in']
                                                ? Carbon::createFromFormat('H:i:s', $record['scheduled_time_in'])->format('g:i A')
                                                : '-' }}
                                        </td>

                                        <td>
                                            @if($isFuture)
                                                <span class="text-muted">Upcoming</span>
                                            @else
                                                {{ isset($record['check_in']) && $record['check_in']
                                                    ? Carbon::createFromFormat('H:i:s', $record['check_in'])->format('g:i A')
                                                    : '-' }}
                                            @endif
                                        </td>

                                        <td>
                                            @if($isFuture)
                                                <span class="text-muted">Upcoming</span>
                                            @else
                                                {{ isset($record['check_out']) && $record['check_out']
                                                    ? Carbon::createFromFormat('H:i:s', $record['check_out'])->format('g:i A')
                                                    : '-' }}
                                            @endif
                                        </td>

                                        <td>
                                            @if($isFuture)
                                                <span class="badge badge-secondary badge-pill">Not Available</span>
                                            @else
                                                <span class="badge {{ $statusClass }} badge-pill">{{ $statusLabel }}</span>
                                            @endif
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
    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('plugins/RWD-Table-Patterns/dist/js/rwd-table.min.js') }}"></script>
<script>
    $(function () {
        $('.table-responsive').responsiveTable({
            addDisplayAllBtn: 'btn btn-secondary'
        });
    });
</script>
@endsection
