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
        <p class="text-muted mb-4">This is a practice login screen only.</p>

        <form action="{{ route('dashboard') }}" method="GET">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" class="form-control" placeholder="admin@ceylontrails.test">
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control" placeholder="Enter password">
            </div>

            <button type="submit" class="btn btn-success w-100">Login</button>
        </form>

        <div class="admin-login-links">
            <a href="{{ route('home') }}">Back to public site</a>
        </div>
    </section>
</main>

@include('admin.components.scripts')
