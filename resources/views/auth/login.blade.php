<x-guest-layout>
    <div class="card card-plain">
        <div class="card-header text-center bg-transparent">
            <h3 class="font-weight-bolder text-gradient mb-0">Welcome Back</h3>
            <p class="mb-0 text-sm text-dark">Enter your credentials to sign in</p>
        </div>
        <div class="card-body px-md-4 py-md-4">
            <form method="POST" action="{{ route('login') }}" role="form">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-dark">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="Email" 
                               value="{{ old('email') }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback" style="display: block;">
                                <strong>{{ $message }}</strong>
                                @if(str_contains($message, 'do not match'))
                                    <br>
                                    <small>Please check your email and password, or contact an administrator if you believe your account was deleted.</small>
                                @endif
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-dark">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Password" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label text-dark" for="remember_me">Remember me</label>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-primary w-100 mt-4 mb-0">Sign in</button>
                </div>
            </form>
        </div>
        <div class="card-footer text-center pt-0 px-lg-2 px-1">
            <p class="mb-4 text-sm mx-auto">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-primary font-weight-bold">Sign up</a>
            </p>
            @if (Route::has('password.request'))
                <small>
                    <a href="{{ route('password.request') }}" class="text-primary">
                        Forgot password?
                    </a>
                </small>
            @endif
        </div>
    </div>
</x-guest-layout>
