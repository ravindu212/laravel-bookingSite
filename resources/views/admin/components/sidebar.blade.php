@php
    $active = $active ?? 'dashboard';
@endphp

<aside class="admin-sidebar">
    <div class="admin-sidebar__brand">
        <span class="admin-sidebar__mark">CT</span>
        <div>
            <strong>Ceylon Trails</strong>
            <small>Travel CMS</small>
        </div>
    </div>

    <nav class="admin-sidebar__nav" aria-label="Admin sections">
        <a class="admin-sidebar__link {{ $active === 'create' || $active === 'dashboard' ? 'is-active' : '' }}" href="{{ route('dashboard') }}#create-stay">
            <span>01</span>
            Create stay
        </a>
        <a class="admin-sidebar__link {{ $active === 'manage' ? 'is-active' : '' }}" href="{{ route('dashboard') }}#manage-stays">
            <span>02</span>
            All stays
        </a>
        <a class="admin-sidebar__link {{ $active === 'edit' ? 'is-active' : 'is-muted' }}" href="{{ $active === 'edit' ? url()->current() : '#' }}" aria-disabled="{{ $active === 'edit' ? 'false' : 'true' }}">
            <span>03</span>
            Edit stay
        </a>
        <a class="admin-sidebar__link {{ $active === 'bookings' ? 'is-active' : '' }}" href="{{ route('admin.bookings.index') }}">
            <span>04</span>
            Bookings
        </a>
        <a class="admin-sidebar__link is-muted" href="#" aria-disabled="true">
            <span>05</span>
            Messages
        </a>
        <a class="admin-sidebar__link is-muted" href="#" aria-disabled="true">
            <span>06</span>
            Gallery
        </a>
    </nav>

    <div class="admin-sidebar__footer">
        <span>Public preview</span>
        <a href="{{ route('home') }}">Open site</a>
    </div>
</aside>
