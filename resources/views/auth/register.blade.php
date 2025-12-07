<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Hr System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('admin/css/auth.css') }}" rel="stylesheet"> 
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <div class="container">
        <div class="login-card card mx-auto">
            <div class="login-header">
                <h4 class="mb-0 fw-bold">Hr System</h4>
            </div>

            <div class="card-body p-4 pt-5">
                <div class="text-center mb-4">
                    <h5 class="fw-bold text-dark">Register New Account</h5>
                    <div class="divider"></div>
                </div>

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold small text-muted">Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user text-secondary"></i></span> 
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter Name">

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold small text-muted">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope text-secondary"></i></span>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter Email">
                            
                            {{-- AJAX Feedback Icon Container --}}
                            <div class="input-group-text p-0 border-0" id="email-feedback-icon"></div>
                            
                            {{-- Server-side validation error --}}
                            @error('email')
                                <span class="invalid-feedback" role="alert" id="email-server-error">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                            {{-- Custom client-side feedback elements --}}
                            <div class="invalid-feedback d-none" id="email-ajax-error">
                                This email is already registered.
                            </div>
                            <div class="valid-feedback d-none" id="email-ajax-success">
                                Email is available!
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold small text-muted">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock text-secondary"></i></span>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Choose Password">
                            
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="far fa-eye-slash" id="toggleIcon"></i>
                            </button>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label fw-bold small text-muted">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock text-secondary"></i></span>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                            
                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                <i class="far fa-eye-slash" id="toggleIconConfirm"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-bd-primary py-2" id="registerSubmitBtn">
                            <i class="fas fa-user-plus me-2"></i> {{ __('Register') }}
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">Already have an account?</p>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--bd-green);">Login Here</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- Password Toggle Functionality ---
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        togglePassword.addEventListener('click', function (e) {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });

        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const passwordConfirmInput = document.getElementById('password-confirm');
        const toggleIconConfirm = document.getElementById('toggleIconConfirm');

        togglePasswordConfirm.addEventListener('click', function (e) {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
            toggleIconConfirm.classList.toggle('fa-eye');
            toggleIconConfirm.classList.toggle('fa-eye-slash');
        });
        
        // --- AJAX Email Uniqueness Check ---
        $(document).ready(function() {
            let emailCheckTimer;
            const emailInput = $('#email');
            const submitBtn = $('#registerSubmitBtn');
            const emailAjaxError = $('#email-ajax-error');
            const emailAjaxSuccess = $('#email-ajax-success');
            const emailServerErr = $('#email-server-error');
            const feedbackIconContainer = $('#email-feedback-icon');
            
            // Initially disable submit button
            submitBtn.prop('disabled', true);
            
            // Debounce function
            function debounce(func, delay) {
                let timer;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(() => {
                        func.apply(context, args);
                    }, delay);
                };
            }
            
            // Function to update the form state
            function updateFormState(isUnique) {
                // Remove server errors from previous submission
                emailServerErr.addClass('d-none');
                
                if (isUnique) {
                    emailInput.removeClass('is-invalid').addClass('is-valid');
                    emailAjaxError.addClass('d-none');
                    emailAjaxSuccess.removeClass('d-none');
                    feedbackIconContainer.html('<i class="fas fa-check text-success px-2"></i>');
                    // Enable submit only if unique
                    submitBtn.prop('disabled', false); 
                } else {
                    emailInput.removeClass('is-valid').addClass('is-invalid');
                    emailAjaxError.removeClass('d-none');
                    emailAjaxSuccess.addClass('d-none');
                    feedbackIconContainer.html('<i class="fas fa-times text-danger px-2"></i>');
                    // Disable submit if not unique
                    submitBtn.prop('disabled', true); 
                }
            }
            
            emailInput.on('keyup blur', debounce(function() {
                const email = $(this).val();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 

                // 1. Clear previous feedback/validation state
                emailAjaxError.addClass('d-none');
                emailAjaxSuccess.addClass('d-none');
                emailInput.removeClass('is-valid is-invalid');
                feedbackIconContainer.empty();
                submitBtn.prop('disabled', true);

                if (email.length < 5 || !emailRegex.test(email)) {
                    // If validation is poor, just stop and keep the button disabled
                    return; 
                }
                
                // Show checking spinner
                feedbackIconContainer.html('<i class="fas fa-spinner fa-spin text-secondary px-2"></i>');

                // 2. Perform AJAX check
                $.ajax({
                    url: '{{ route('register.check.email') }}',
                    method: 'POST',
                    data: { 
                        email: email, 
                        _token: $('meta[name="csrf-token"]').attr('content') // Use meta tag if available, fallback to hardcoded
                    },
                    success: function(response) {
                        updateFormState(response.is_unique);
                    },
                    error: function() {
                        // On network error, revert feedback but allow server validation as fallback
                        emailInput.removeClass('is-valid is-invalid');
                        feedbackIconContainer.html('<i class="fas fa-exclamation-triangle text-warning px-2"></i>');
                        submitBtn.prop('disabled', false); 
                    }
                });
            }, 500)); // 500ms debounce
            
            // Trigger check on load if old input exists (e.g., if other fields failed validation)
            if (emailInput.val().length > 0 && emailServerErr.length > 0 && !emailServerErr.is(':visible')) {
                emailInput.trigger('keyup'); 
            }
        });
    </script>
</body>
</html>