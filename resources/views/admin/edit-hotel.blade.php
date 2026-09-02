@include('admin.components.header')
@include('admin.components.nav')

<main class="admin-shell">
    <div class="admin-layout">
        @include('admin.components.sidebar', ['active' => 'edit'])

        <div class="admin-main">
            @include('admin.components.page-header', [
                'title' => 'Edit stay',
                'description' => 'Update this travel card and keep the public site fresh.',
                'actionHref' => route('dashboard'),
                'actionLabel' => 'Back to dashboard',
            ])

            <div class="admin-form-wrap">
                @include('admin.components.hotel-form', [
                    'hotel' => $hotel,
                    'action' => route('admin.hotels.update', $hotel),
                    'method' => 'PUT',
                    'eyebrow' => 'Update stay',
                    'formTitle' => $hotel->name,
                    'formDescription' => 'Edit the fields and save your changes.',
                    'submitLabel' => 'Save changes',
                    'showCancel' => true,
                ])
            </div>
        </div>
    </div>
</main>

@include('admin.components.scripts')
