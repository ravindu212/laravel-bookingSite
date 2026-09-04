@include('admin.components.header')
@include('admin.components.nav')

<main class="admin-shell">
    <div class="admin-layout">
        @include('admin.components.sidebar', ['active' => 'inventory'])

        <div class="admin-main">
            @include('admin.components.page-header', [
                'title' => 'Edit inventory',
                'description' => 'Update this package or menu item for '.$hotel->name.'.',
                'actionHref' => route('admin.hotels.inventories', $hotel),
                'actionLabel' => 'Back to inventory',
            ])

            <div class="admin-form-wrap">
                @include('admin.components.inventory-form', [
                    'hotel' => $hotel,
                    'inventory' => $inventory,
                    'action' => route('admin.hotels.inventories.update', [$hotel, $inventory]),
                    'method' => 'PUT',
                    'formTitle' => $inventory->name,
                    'formDescription' => 'Edit the item details and save changes.',
                    'submitLabel' => 'Save changes',
                ])
            </div>
        </div>
    </div>
</main>

@include('admin.components.scripts')
