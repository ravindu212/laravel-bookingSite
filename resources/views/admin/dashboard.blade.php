@include('admin.components.header')
@include('admin.components.nav')

<main class="admin-shell">
    <div class="admin-layout">
        @include('admin.components.sidebar', ['active' => 'dashboard'])

        <div class="admin-main">
            @include('admin.components.page-header', [
                'title' => 'Dashboard',
                'description' => 'Create Sri Lankan hotel cards for the public Featured stays section.',
                'actionHref' => route('home'),
                'actionLabel' => 'View public site',
            ])

            <div class="admin-form-wrap">
                @include('admin.components.hotel-form', [
                    'action' => route('admin.hotels.store'),
                    'formTitle' => 'Add a public travel card',
                    'formDescription' => 'Save one hotel, villa, or travel place. It appears on the public home page.',
                ])

                @include('admin.components.hotel-table', ['hotels' => $hotels])
            </div>
        </div>
    </div>
</main>

@include('admin.components.scripts')
