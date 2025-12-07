<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Hr System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('admin/css/auth.css') }}" rel="stylesheet">
</head>
<body>

    <div class="container">
        <div class="login-card card mx-auto">
            <div class="login-header">
               
                <h4 class="mb-0 fw-bold">Hr System</h4>
            </div>

            <div class="card-body p-4 pt-5">
                <div class="text-center mb-4">
                    <h5 class="fw-bold text-dark">Login to Portal</h5>
                    <div class="divider"></div>
                </div>

                <form method="POST" action="{{ route('login') }}">
                        @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold small text-muted">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope text-secondary"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email" required>

                            @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                    </div>

                    <div class="mb-4">
    <label for="password" class="form-label fw-bold small text-muted">Password</label>
    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-lock text-secondary"></i></span>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter Password" required>
        
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

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label small" for="rememberMe">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="small text-decoration-none text-danger fw-bold">Forgot Password?</a>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-bd-primary py-2">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">Don't have an account?</p>
                    <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: var(--bd-green);">Register Here</a>
                </div>
            </div>

            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        togglePassword.addEventListener('click', function (e) {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle the eye icon class
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>

