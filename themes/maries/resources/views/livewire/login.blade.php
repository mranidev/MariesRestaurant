<div>
    @if ($customer)
        <div class="text-center py-4">
            <div class="mb-2">
                <span
                    class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success"
                    style="width:64px;height:64px;font-size:1.6rem;"
                ><i class="bi bi-person-check"></i></span>
            </div>
            <h4 class="fw-bold mb-1">Welcome back, {{ $customer->first_name }}</h4>
            <p class="text-muted mb-3">{{ $customer->email }}</p>
            <button
                type="button"
                class="btn btn-outline-danger rounded-pill px-4"
                wire:click="onLogout"
            >Log out</button>
        </div>
    @else
        <h1 class="card-title h4 mb-4 fw-normal">Log In</h1>

        <form wire:submit="onLogin" novalidate>
            <div class="form-group mb-3">
                <div class="input-group">
                    <input
                        type="email"
                        wire:model="email"
                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                        placeholder="Email Address"
                        autocomplete="email"
                        required
                    />
                    <span class="input-group-text">@</span>
                </div>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <div class="input-group">
                    <input
                        type="password"
                        wire:model="password"
                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                        placeholder="Password"
                        autocomplete="current-password"
                        required
                    />
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <div class="d-md-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input
                            id="rememberLogin"
                            class="form-check-input"
                            type="checkbox"
                            wire:model="remember"
                            name="remember"
                            value="1"
                        />
                        <label class="form-check-label" for="rememberLogin">Remember me</label>
                    </div>
                </div>
            </div>

            @if ($errorMessage && !$errors->has('email'))
                <div class="error-message mb-3" style="display: block;">{{ $errorMessage }}</div>
            @endif

            <div class="form-group mb-4">
                <button
                    type="submit"
                    class="btn btn-primary w-100 btn-lg rounded-pill"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Login</span>
                    <span wire:loading>Signing in&hellip;</span>
                </button>
            </div>
        </form>

        <div class="text-center text-muted">
            Don't have an account?
            <a class="fw-bold text-decoration-none" href="{{ url('/register') }}">Register</a>
        </div>
    @endif
</div>
