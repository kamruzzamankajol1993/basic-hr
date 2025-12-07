@extends('admin.master.master')

@section('title', 'Edit Employee: ' . $employee->first_name)

@section('css')
    @endsection

@section('body')
<div class="main-content container-fluid pt-5 mt-5">
    <h1 class="h3 mb-4 text-gray-800 fw-bold" style="color: var(--bd-green);">Edit Employee: {{ $employee->first_name }} {{ $employee->last_name }}</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Update Employee Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $employee->first_name) }}" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $employee->last_name) }}" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $employee->email) }}" required data-employee-id="{{ $employee->id }}">
                        <small id="emailHelp" class="form-text"></small>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label class="form-label d-block">Skills (Optional)</label>
                        <div id="skills-container" class="border p-3 rounded bg-light">
                            @php
                                // Use old input if validation failed, otherwise use current employee skills
                                $selectedSkills = old('skills', $currentSkillIds);
                            @endphp
                            
                            @foreach($selectedSkills as $skillId)
                                <div class="input-group mb-2 skill-group">
                                    <select name="skills[]" class="form-select" required>
                                        <option value="">Select Skill</option>
                                        @foreach($skills as $skill)
                                            <option value="{{ $skill->id }}" {{ $skill->id == $skillId ? 'selected' : '' }}>{{ $skill->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-danger remove-skill"><i class="fas fa-times"></i></button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-skill-btn" class="btn btn-sm btn-outline-success mt-2">
                            <i class="fas fa-plus"></i> Add Skill
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning mt-3">Update Employee</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
// Same script as create.blade.php but with an initial check for email uniqueness and employee ID passed for exclusion
$(document).ready(function() {
    
    // --- 1. Dynamic Skill Management (Repeater) ---
    const skillOptions = `@foreach($skills as $skill) <option value="{{ $skill->id }}">{{ $skill->name }}</option> @endforeach`;
    const employeeId = $('#email').data('employee-id'); // Get the ID for uniqueness check

    function addSkillField() {
        const newSkillHtml = `
            <div class="input-group mb-2 skill-group">
                <select name="skills[]" class="form-select" required>
                    <option value="">Select Skill</option>
                    ${skillOptions}
                </select>
                <button type="button" class="btn btn-danger remove-skill"><i class="fas fa-times"></i></button>
            </div>
        `;
        $('#skills-container').append(newSkillHtml);
    }
    
    $('#add-skill-btn').on('click', function() {
        addSkillField();
    });

    $(document).on('click', '.remove-skill', function() {
        $(this).closest('.skill-group').remove();
    });

    // --- 2. Real-time AJAX Email Uniqueness Check (Adapted for Edit) ---
    let emailCheckTimer;
    const emailInput = $('#email');
    const emailHelp = $('#emailHelp');

    emailInput.on('keyup', function() {
        clearTimeout(emailCheckTimer);
        const email = $(this).val();

        if (email.length < 5 || !email.includes('@')) {
            emailHelp.text('Enter a valid email address.');
            emailHelp.removeClass('text-success text-danger');
            emailInput.removeClass('is-valid is-invalid');
            return;
        }

        emailHelp.html('<i class="fas fa-spinner fa-spin me-1"></i> Checking uniqueness...');
        emailHelp.removeClass('text-success text-danger');
        emailInput.removeClass('is-valid is-invalid');

        emailCheckTimer = setTimeout(function() {
            $.ajax({
                url: '{{ route('employees.check.email') }}',
                method: 'POST',
                data: { 
                    email: email, 
                    id: employeeId, // Pass the employee ID to ignore its own email
                    _token: '{{ csrf_token() }}' 
                },
                success: function(response) {
                    if (response.is_unique) {
                        emailHelp.text('Email is available.');
                        emailHelp.removeClass('text-danger').addClass('text-success');
                        emailInput.removeClass('is-invalid').addClass('is-valid');
                    } else {
                        emailHelp.text('Email is already taken by another employee.');
                        emailHelp.removeClass('text-success').addClass('text-danger');
                        emailInput.removeClass('is-valid').addClass('is-invalid');
                    }
                },
                error: function() {
                    emailHelp.text('Error checking email.');
                    emailHelp.addClass('text-danger');
                }
            });
        }, 800);
    });
    
    // Initial check (optional, but good for UX if email hasn't changed)
    emailInput.trigger('keyup'); 

});
</script>
@endsection