@extends('layouts.master')

@section('css')
    <!-- Optional CSS for this page -->
@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title text-left">Leave Request</h4>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item active">Leave Request</li>
    </ol>
</div>
@endsection

@section('content')
@include('includes.flash') <!-- Use your standard flash include -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="mb-4">Leave Request Form</h4>

                <form action="{{ route('user.leave.submit') }}" method="POST" class="mt-3">
                    @csrf

                    <div class="form-group d-flex justify-content-between align-items-center mb-4">
                        <label for="type" class="me-3 mb-0">Leave Type</label>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-primary {{ old('type') == 1 ? 'active' : '' }}">
                                <input type="radio" name="type" id="type_annual" value="1" {{ old('type') == 1 ? 'checked' : '' }} required> Annual Leave
                            </label>
                            <label class="btn btn-outline-primary {{ old('type') == 2 ? 'active' : '' }}">
                                <input type="radio" name="type" id="type_sick" value="2" {{ old('type') == 2 ? 'checked' : '' }} required> Sick Leave
                            </label>
                            <label class="btn btn-outline-primary {{ old('type') == 3 ? 'active' : '' }}">
                                <input type="radio" name="type" id="type_casual" value="3" {{ old('type') == 3 ? 'checked' : '' }} required> Casual Leave
                            </label>
                        </div>
                        @error('type')
                            <div class="invalid-feedback d-block ms-3">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="leave_date" class="form-label">Start Date</label>
                        <input type="date" name="leave_date" id="leave_date" class="form-control @error('leave_date') is-invalid @enderror" value="{{ old('leave_date') }}" required>
                        @error('leave_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date (optional)</label>
                        <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Must be same or after Start Date.</small>
                    </div>

                    <div class="mb-4">
                        <label for="reason" class="form-label">Reason</label>
                        <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Leave Request</button>
                </form>

            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection
