<div>
    @if ($customer)
        <div class="text-center py-4">
            <div class="mb-2">
                <span
                    class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 text-success"
                    style="width:64px;height:64px;font-size:1.6rem;"
                ><i class="bi bi-person-check"></i></span>
            </div>
            <h4 class="fw-bold mb-1">You're signed in, {{ $customer->first_name }}</h4>
            <p class="text-muted mb-3">{{ $customer->email }}</p>
            <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4">Continue</a>
        </div>
    @else
        <h1 class="card-title h4 mb-4 fw-normal">Create an Account</h1>

        <form wire:submit="onRegister" novalidate>
            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <input
                        type="text"
                        wire:model="firstName"
                        class="form-control form-control-lg @error('firstName') is-invalid @enderror"
                        placeholder="First name"
                        autocomplete="given-name"
                    />
                    @error('firstName')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6">
                    <input
                        type="text"
                        wire:model="lastName"
                        class="form-control form-control-lg @error('lastName') is-invalid @enderror"
                        placeholder="Last name"
                        autocomplete="family-name"
                    />
                    @error('lastName')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6">
                    <input
                        type="email"
                        wire:model="email"
                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                        placeholder="Email Address"
                        autocomplete="email"
                    />
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-sm-6">
                    <input
                        type="text"
                        wire:model="telephone"
                        class="form-control form-control-lg @error('telephone') is-invalid @enderror"
                        placeholder="Phone"
                        autocomplete="tel"
                    />
                    @error('telephone')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <input
                        type="password"
                        wire:model="password"
                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                        placeholder="Password (min 6 characters)"
                        autocomplete="new-password"
                    />
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            @if ($errorMessage)
                <div class="error-message mb-3" style="display: block;">{{ $errorMessage }}</div>
            @endif

            <div class="form-group mb-4">
                <button
                    type="submit"
                    class="btn btn-primary w-100 btn-lg rounded-pill"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Create Account</span>
                    <span wire:loading>Creating&hellip;</span>
                </button>
            </div>
        </form>

        <div class="text-center text-muted">
            Already have an account?
            <a class="fw-bold text-decoration-none" href="{{ url('/login') }}">Login</a>
        </div>
    @endif
</div>
