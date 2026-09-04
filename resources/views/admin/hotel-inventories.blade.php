@include('admin.components.header')
@include('admin.components.nav')

<main class="admin-shell">
    <div class="admin-layout">
        @include('admin.components.sidebar', ['active' => 'inventory'])

        <div class="admin-main">
            @include('admin.components.page-header', [
                'title' => 'Hotel inventory',
                'description' => 'Add foods, package items, transport, and other details for '.$hotel->name.'.',
                'actionHref' => route('dashboard'),
                'actionLabel' => 'Back to dashboard',
            ])

            <div class="admin-form-wrap">
                @if(session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="row g-4">
                    <div class="col-lg-7">
                        @include('admin.components.inventory-form', [
                            'hotel' => $hotel,
                            'action' => route('admin.hotels.inventories.store', $hotel),
                        ])
                    </div>
                    <div class="col-lg-5">
                        @include('admin.components.inventory-import', ['hotel' => $hotel])
                    </div>
                </div>

                @include('admin.components.inventory-table', ['inventories' => $hotel->inventories])
            </div>
        </div>
    </div>
</main>

@include('admin.components.scripts')
