<!-- Edit Modal -->
<div id="editEmployee{{ $employee->id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editEmployeeLabel{{ $employee->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('employees.update', $employee->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeLabel{{ $employee->id }}">Edit Employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Name Input -->
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required placeholder="Name">
                    </div>

                    <!-- Position Input -->
                    <div class="form-group">
                        <input type="text" name="position" class="form-control" value="{{ $employee->position }}" required placeholder="Position">
                    </div>

                    <!-- Email Input -->
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" value="{{ $employee->email }}" required placeholder="Email">
                    </div>

                    <!-- PIN Code Input -->
                    <div class="form-group">
                        <input type="password" name="pin_code" class="form-control" placeholder="New PIN Code (leave blank if no change)">
                    </div>

                    <!-- Schedule Dropdown -->
                    <div class="form-group">
                        <select name="schedule" class="form-control" required>
                            <option value="">Select Schedule</option>
                            @foreach ($schedules as $schedule)
                                <option value="{{ $schedule->slug }}" {{ optional($employee->schedules->first())->slug == $schedule->slug ? 'selected' : '' }}>
                                    {{ $schedule->slug }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- is_approved switch -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_approved" id="isApproved{{ $employee->id }}"
                            {{ optional($employee->user)->is_approved ? 'checked' : '' }}>
                        <label class="form-check-label" for="isApproved{{ $employee->id }}">Approved</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteEmployee{{ $employee->id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteEmployeeLabel{{ $employee->id }}" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form method="POST" action="{{ route('employees.destroy', $employee->id) }}">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h5 id="deleteEmployeeLabel{{ $employee->id }}">Delete Employee</h5>
                    <p>Are you sure you want to delete <strong>{{ $employee->name }}</strong>?</p>
                    <button type="submit" class="btn btn-danger mr-2">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
