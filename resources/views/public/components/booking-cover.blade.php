<div class="booking-cover">
    <img src="{{ $imageUrl }}" alt="{{ $hotel->name }}">

    <div class="booking-cover__content">
        <div>
            <span class="travel-eyebrow">Booking page</span>
            <h1 class="fw-bold mb-3">{{ $hotel->name }}</h1>
            <p class="mb-0">{{ $hotel->description ?: 'Plan your stay and send a simple booking request.' }}</p>
        </div>

        <div class="booking-cover__actions">
            @if($hotel->location)
                <span>{{ $hotel->location }}</span>
            @endif
            <a href="{{ route('home') }}#stays" class="btn btn-light">Back to stays</a>
        </div>
    </div>
</div>
