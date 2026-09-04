<section class="admin-card mt-4" id="manage-stays">
    <div class="admin-card__header mb-4">
        <div>
            <p class="admin-eyebrow mb-2">Manage stays</p>
            <h2 class="h4 fw-bold mb-1">Existing travel cards</h2>
            <p class="text-muted mb-0">Update or remove stays that appear on the public home page.</p>
        </div>
    </div>

    @if($hotels->isNotEmpty())
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Contact</th>
                        <th>Website</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hotels as $hotel)
                        <tr>
                            <td>
                                <strong>{{ $hotel->name }}</strong>
                                <small>{{ $hotel->description ?: 'No description' }}</small>
                            </td>
                            <td>{{ $hotel->location ?: 'Not set' }}</td>
                            <td>
                                <span>{{ $hotel->phone ?: 'No phone' }}</span>
                                <small>{{ $hotel->email ?: 'No email' }}</small>
                            </td>
                            <td>
                                @if($hotel->website)
                                    <a href="{{ $hotel->website }}" target="_blank" rel="noopener">Visit</a>
                                @else
                                    Not set
                                @endif
                            </td>
                            <td>
                                <div class="admin-table__actions">
                                    <a href="{{ route('hotels.booking', $hotel) }}" class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener">Booking</a>
                                    <a href="{{ route('admin.hotels.inventories', $hotel) }}" class="btn btn-sm btn-outline-primary">Inventory</a>
                                    <a href="{{ route('admin.hotels.edit', $hotel) }}" class="btn btn-sm btn-outline-success">Edit</a>
                                    <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST" onsubmit="return confirm('Delete this stay?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="admin-empty">
            <strong>No stays yet</strong>
            <p class="mb-0">Create your first travel card using the form above.</p>
        </div>
    @endif
</section>
