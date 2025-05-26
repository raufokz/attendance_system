@extends('layouts.master')

@section('css')
<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --success: #4cc9f0;
        --light: #f8f9fa;
        --dark: #212529;
    }

    .stat-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 24px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }

    .stat-card.bg-secondary {
        background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%) !important;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        background: rgba(255,255,255,0.2);
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

    .percentage-badge {
        font-size: 14px;
        padding: 4px 8px;
        border-radius: 50px;
        background: rgba(255,255,255,0.2);
    }

    @media (max-width: 768px) {
        .stat-value {
            font-size: 28px;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            font-size: 20px;
        }
    }
</style>
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Dashboard</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Welcome to Simple Attendance Management System</li>
    </ol>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- First Row -->
    <div class="row">
        <!-- Total Employees -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-secondary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon mr-3">
                            <i class="ti-id-badge"></i>
                        </div>
                        <div>
                            <h5 class="stat-title text-white-50">Total Employees</h5>
                            <h1 class="stat-value">{{ $data[0] }}</h1>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="ti-user" style="font-size: 48px; opacity: 0.2;"></span>
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- On Time Today -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-secondary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon mr-3">
                            <i class="ti-check-box"></i>
                        </div>
                        <div>
                            <h5 class="stat-title text-white-50">On Time Today</h5>
                            <h1 class="stat-value">{{ $data[1] }}</h1>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="percentage-badge">{{ round(($data[1]/$data[0])*100) }}% of staff</span>
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Late Today -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-secondary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon mr-3">
                            <i class="ti-alert"></i>
                        </div>
                        <div>
                            <h5 class="stat-title text-white-50">Late Today</h5>
                            <h1 class="stat-value">{{ $data[2] }}</h1>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="percentage-badge">{{ round(($data[2]/$data[0])*100) }}% of staff</span>
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- On Time Percentage -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-secondary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon mr-3">
                            <i class="ti-alarm-clock"></i>
                        </div>
                        <div>
                            <h5 class="stat-title text-white-50">On Time Percentage</h5>
                            <h1 class="stat-value">{{ $data[3] }}%</h1>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ $data[3] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row mt-4">
        <!-- Available Schedules -->
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-secondary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon mr-3">
                            <i class="ti-panel"></i>
                        </div>
                        <div>
                            <h5 class="stat-title text-white-50">Available Schedules</h5>
                            <h1 class="stat-value">{{ $data[4] }}</h1>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="ti-time" style="font-size: 48px; opacity: 0.2;"></span>
                        <a href="#" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Cards Can Go Here -->
        <div class="col-xl-9">
            <div class="stat-card h-100">
                <div class="card-body">
                    <h4 class="header-title mb-4">Monthly Attendance Overview</h4>
                    <div id="attendance-chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Chartist Chart -->
<script src="{{ URL::asset('plugins/chartist/js/chartist.min.js') }}"></script>
<script src="{{ URL::asset('plugins/chartist/js/chartist-plugin-tooltip.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    new Chartist.Line('#attendance-chart', {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        series: [
            [12, 9, 7, 8, 5, 4, 6],
            [2, 1, 3.5, 7, 3, 5, 3],
            [1, 3, 4, 5, 6, 3, 2]
        ]
    }, {
        fullWidth: true,
        chartPadding: {
            right: 40
        },
        plugins: [
            Chartist.plugins.tooltip()
        ]
    });
});
</script>
@endsection
