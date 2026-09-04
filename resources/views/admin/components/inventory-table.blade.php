<section class="admin-card mt-4">
    <div class="admin-card__header mb-4">
        <div>
            <p class="admin-eyebrow mb-2">Current inventory</p>
            <h2 class="h4 fw-bold mb-1">Items in this hotel package</h2>
            <p class="text-muted mb-0">These details show on the public booking page.</p>
        </div>
    </div>

    @if($inventories->isNotEmpty())
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Item</th>
                        <th>Price</th>
                        <th>People</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventories as $inventory)
                        <tr>
                            <td>{{ $inventory->category }}</td>
                            <td>{{ $inventory->menu_type ?: 'Not set' }}</td>
                            <td><strong>{{ $inventory->name }}</strong></td>
                            <td>
                                @if($inventory->price)
                                    LKR {{ number_format((float) $inventory->price, 2) }}
                                @else
                                    Not set
                                @endif
                            </td>
                            <td>{{ $inventory->people_count ? $inventory->people_count.' people' : 'Not set' }}</td>
                            <td>{{ $inventory->description ?: 'No description' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="admin-empty">
            <strong>No inventory yet</strong>
            <p class="mb-0">Add foods, package items, and extras for this hotel.</p>
        </div>
    @endif
</section>
