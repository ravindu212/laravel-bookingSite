<nav class="navbar navbar-expand-lg navbar-dark admin-topbar">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">Admin Panel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav"
      aria-controls="adminNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.bookings.index') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">Bookings</a>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('home') }}">View Site</a>
        </li>
        <li class="nav-item">
          <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link admin-logout-button">Logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>
