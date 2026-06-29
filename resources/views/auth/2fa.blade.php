@extends('layouts.guest')

<style>
    .tracking-wide {
        letter-spacing: 6px;
        font-size: 20px;
        font-weight: 600;
    }

    .auth-center {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f7fb;
    }

    .auth-card {
        width: 420px;
        border-radius: 12px;
        border: none;
    }
</style>

@section('content')
<div class="auth-center">

    <div class="card shadow-lg auth-card">

        <div class="card-body p-4">

            <div class="text-center mb-4">
                <h4 class="fw-bold">Two-Factor Authentication</h4>
                <p class="text-muted small">
                    Enter the 6-digit code from Google Authenticator
                </p>
            </div>

            <form method="POST" action="{{ route('2fa.verify') }}">
                @csrf

                <div class="mb-3 text-center">
                    <label class="form-label">Authentication Code</label>

                    <input
                        type="text" name="otp" class="form-control form-control-lg text-center tracking-wide" maxlength="6" placeholder="------" required autofocus>

                    @error('otp')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <button class="btn btn-primary w-100 btn-lg">
                    Verify
                </button>
            </form>

            <div class="text-center mt-3">
                <small class="text-muted">
                    Open your Google Authenticator app
                </small>
            </div>

        </div>
    </div>

</div>
@endsection