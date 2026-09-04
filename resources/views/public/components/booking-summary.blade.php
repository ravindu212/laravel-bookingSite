<div class="booking-summary">
    <span class="travel-eyebrow travel-eyebrow--dark">Booking page</span>
    <img src="{{ $imageUrl }}" class="booking-image mb-4" alt="{{ $hotel->name }}">
    <h1 class="fw-bold mb-3">{{ $hotel->name }}</h1>
    <p class="text-muted mb-4">{{ $hotel->description ?: 'Plan your stay and send a simple booking request.' }}</p>

    <div class="booking-info">
        <p><strong>Location:</strong> {{ $hotel->location ?: 'Not set' }}</p>
        <p><strong>Phone:</strong> {{ $hotel->phone ?: 'Not set' }}</p>
        <p><strong>Email:</strong> {{ $hotel->email ?: 'Not set' }}</p>
    </div>

    @include('public.components.hotel-inventory', ['inventories' => $hotel->inventories])

    <div class="d-flex flex-wrap gap-2 mt-4">
        @if($hotel->website)
            <a href="{{ $hotel->website }}" class="btn btn-success" target="_blank" rel="noopener">Visit hotel website</a>
        @endif
        <a href="{{ route('home') }}#stays" class="btn btn-outline-success">Back to stays</a>
    </div>
</div>
