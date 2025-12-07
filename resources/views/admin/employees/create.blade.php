@extends('admin.master.master')

@section('title', 'Create Employee')

@section('css')
    @endsection

@section('body')
<div class="main-content container-fluid pt-5 mt-5">
    <h1 class="h3 mb-4 text-gray-800 fw-bold" style="color: var(--bd-green);">Create New Employee</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Employee Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('employees.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        <small id="emailHelp" class="form-text"></small>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label class="form-label d-block">Skills (Optional)</label>
                        <div id="skills-container" class="border p-3 rounded bg-light">
                            @if(old('skills'))
                                @foreach(old('skills') as $skillId)
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
                            @endif
                            
                            </div>
                        <button type="button" id="add-skill-btn" class="btn btn-sm btn-outline-success mt-2">
                            <i class="fas fa-plus"></i> Add Skill
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-bd-primary mt-3">Create Employee</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    
    // --- 1. Dynamic Skill Management (Repeater) ---
    const skillOptions = `@foreach($skills as $skill) <option value="{{ $skill->id }}">{{ $skill->name }}</option> @endforeach`;

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
    
    // Add button handler
    $('#add-skill-btn').on('click', function() {
        addSkillField();
    });

    // Remove button handler (delegated)
    $(document).on('click', '.remove-skill', function() {
        $(this).closest('.skill-group').remove();
    });

    // If no skills are present initially (and no old input), add one empty field
    if ($('#skills-container').children('.skill-group').length === 0 && !'{{ old('skills') }}') {
        // addSkillField(); // Keeping it empty by default unless user clicks add
    }

    
    // --- 2. Additional jQuery Interaction: Real-time AJAX Email Uniqueness Check ---
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
                    _token: '{{ csrf_token() }}' 
                },
                success: function(response) {
                    if (response.is_unique) {
                        emailHelp.text('Email is available.');
                        emailHelp.removeClass('text-danger').addClass('text-success');
                        emailInput.removeClass('is-invalid').addClass('is-valid');
                    } else {
                        emailHelp.text('Email is already taken.');
                        emailHelp.removeClass('text-success').addClass('text-danger');
                        emailInput.removeClass('is-valid').addClass('is-invalid');
                    }
                },
                error: function() {
                    emailHelp.text('Error checking email.');
                    emailHelp.addClass('text-danger');
                }
            });
        }, 800); // Debounce time for AJAX check
    });

});
</script>
@endsection