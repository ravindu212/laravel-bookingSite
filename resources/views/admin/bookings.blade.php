@include('admin.components.header')
@include('admin.components.nav')

<main class="admin-shell">
    <div class="admin-layout">
        @include('admin.components.sidebar', ['active' => 'bookings'])

        <div class="admin-main">
            @include('admin.components.page-header', [
                'title' => 'Bookings',
                'description' => 'View booking requests sent from the public hotel pages.',
                'actionHref' => route('dashboard'),
                'actionLabel' => 'Create stay',
            ])

            @include('admin.components.booking-table', ['bookings' => $bookings])
        </div>
    </div>
</main>

@include('admin.components.scripts')
