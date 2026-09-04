<div class="booking-summary">
    <div class="booking-info">
        <p class="travel-location mb-2">Hotel details</p>
        <p><strong>Location:</strong> {{ $hotel->location ?: 'Not set' }}</p>
        <p><strong>Phone:</strong> {{ $hotel->phone ?: 'Not set' }}</p>
        <p><strong>Email:</strong> {{ $hotel->email ?: 'Not set' }}</p>
    </div>

    @include('public.components.hotel-inventory', ['inventories' => $hotel->inventories])

    <div class="booking-actions">
        @if($hotel->website)
            <a href="{{ $hotel->website }}" class="btn btn-outline-success w-100" target="_blank" rel="noopener">Visit hotel website</a>
        @endif
    </div>
</div>
