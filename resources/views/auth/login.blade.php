<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<div class="container">
    <!-- Logo -->
    <div class="logo-container">
        <img src="img/pgncom.png" alt="Logo">
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Email -->
        <div class="form-group">
            <div class="input-group">
                <span class="input-icon">
                    <i class="fa fa-envelope"></i>
                </span>
                <input id="email" type="email" class="form-control padding-left @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                    placeholder="Email Address">
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <div class="input-group">
                <span class="input-icon">
                    <i class="fa fa-lock"></i>
                </span>
                <input id="password-field" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password" placeholder="Password">
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <!-- Remember Me, Register, Forgot Password -->
        <div class="auth-group">
            <!-- Remember Me -->
            <div>
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember Me</label>
            </div>

            <!-- Register -->
            <!-- <a href="{{ route('register') }}">Register</a> -->

            <!-- Forgot Password -->
            <!-- @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            @endif -->
        </div>

        <!-- Login Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
</div>
</body>

</html>