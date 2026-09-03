@include('admin.components.header')

<main class="admin-login-page">
    <section class="admin-login-card" aria-labelledby="admin-login-title">
        <div class="admin-login-brand">
            <span class="admin-sidebar__mark">CT</span>
            <div>
                <strong>Ceylon Trails</strong>
                <small>Travel CMS</small>
            </div>
        </div>

        <p class="admin-eyebrow mb-2">Admin access</p>
        <h1 id="admin-login-title" class="fw-bold mb-2">Sign in</h1>
        <p class="text-muted mb-4">Login to manage stays and booking requests.</p>

        <form action="{{ route('admin.login.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="admin@ceylontrails.test" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success w-100">Login</button>
        </form>

        <div class="admin-login-links">
            <a href="{{ route('home') }}">Back to public site</a>
            <a href="{{ route('admin.register') }}">Create account</a>
        </div>
    </section>
</main>

@include('admin.components.scripts')
