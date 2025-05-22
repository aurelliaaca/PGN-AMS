<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="container">
    <a href="{{ route('login') }}" class="back-button">
        <i class="fa fa-arrow-left"></i>
    </a>
    <div class="logo-container">
        <img src="{{ asset('img/pgncom.png') }}" alt="Logo" class="logo">
    </div>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group">
            <div class="input-group">
                <span class="input-icon">
                    <i class="fa fa-envelope"></i>
                </span>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                    placeholder="Email Address">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
                <span class="input-icon">
                    <i class="fa fa-lock"></i>
                </span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password" placeholder="Password">
                <span class="eye-icon">
                    <i class="fa fa-eye field-icon toggle-password" id="toggle-password1"></i>
                </span>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <div class="input-group">
                <span class="input-icon">
                    <i class="fa fa-lock"></i>
                </span>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                    autocomplete="new-password" placeholder="Confirm Password">
                <span class="eye-icon">
                    <i class="fa fa-eye field-icon toggle-password" id="toggle-password2"></i>
                </span>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
        </div>
    </form>
</div>
<script>
    const togglePassword1 = document.querySelector("#toggle-password1");
    const togglePassword2 = document.querySelector("#toggle-password2");

    const passwordField = document.querySelector("#password");
    const passwordConfirmField = document.querySelector("#password-confirm");

    togglePassword1.addEventListener("click", function () {
        const type = passwordField.type === "password" ? "text" : "password";
        passwordField.type = type;

        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });

    togglePassword2.addEventListener("click", function () {
        const type = passwordConfirmField.type === "password" ? "text" : "password";
        passwordConfirmField.type = type;

        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
</script>