<nav class="navbar navbar-expand-lg navbar-dark travel-navbar">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('home') }}">Ceylon Trails</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav"
      aria-controls="publicNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="publicNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item active">
          <a class="nav-link" href="{{ route('home') }}">Home</a>
        </li>
        <li class="nav-item">
          @auth
            <a class="nav-link" href="{{ route('dashboard') }}">Admin</a>
          @else
            <a class="nav-link" href="{{ route('admin.login') }}">Admin</a>
          @endauth
        </li>
      </ul>
    </div>
  </div>
</nav>
