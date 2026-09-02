@php
    $imageUrl = data_get($hotel, 'image_url') ?: 'https://commons.wikimedia.org/wiki/Special:FilePath/Nine%20Arches%20Bridge%20in%20Ella.jpg';
@endphp

<article class="card travel-card h-100 border-0">
    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ data_get($hotel, 'name') }}">
    <div class="card-body p-4">
        <p class="travel-location mb-2">{{ data_get($hotel, 'location') }}</p>
        <h5 class="card-title fw-bold">{{ data_get($hotel, 'name') }}</h5>
        <p class="card-text text-muted">{{ data_get($hotel, 'description') }}</p>
        <div class="travel-contact">
            @if(data_get($hotel, 'phone'))
                <span>{{ data_get($hotel, 'phone') }}</span>
            @endif

            @if(data_get($hotel, 'email'))
                <span>{{ data_get($hotel, 'email') }}</span>
            @endif
        </div>
    </div>
    <div class="card-footer bg-white border-0 p-4 pt-0">
        <a href="{{ route('hotels.booking', $hotel) }}" class="btn btn-outline-success w-100">
            View details
        </a>
    </div>
</article>
