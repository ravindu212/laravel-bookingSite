@include('admin.components.header')

<main class="admin-login-page">
    <section class="admin-login-card" aria-labelledby="admin-register-title">
        <div class="admin-login-brand">
            <span class="admin-sidebar__mark">CT</span>
            <div>
                <strong>Ceylon Trails</strong>
                <small>Travel CMS</small>
            </div>
        </div>

        <p class="admin-eyebrow mb-2">Admin account</p>
        <h1 id="admin-register-title" class="fw-bold mb-2">Register</h1>
        <p class="text-muted mb-4">Create an admin account for this practice dashboard.</p>

        <form action="{{ route('admin.register.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Admin user" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="admin@ceylontrails.test" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="At least 8 characters" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Repeat password" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Create account</button>
        </form>

        <div class="admin-login-links">
            <a href="{{ route('home') }}">Back to public site</a>
            <a href="{{ route('admin.login') }}">Already registered?</a>
        </div>
    </section>
</main>

@include('admin.components.scripts')
