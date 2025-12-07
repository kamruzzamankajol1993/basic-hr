<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Hr System</title>
    
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
                    <h5 class="fw-bold text-dark">Password Reset</h5>
                    <div class="divider"></div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success small" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold small text-muted">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope text-secondary"></i></span>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter Email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-bd-primary py-2">
                             <i class="fas fa-paper-plane me-2"></i> {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">Remember your password?</p>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--bd-green);">Go back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>