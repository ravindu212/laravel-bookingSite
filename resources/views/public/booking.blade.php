@include('public.components.header')
@include('public.components.nav')

@php
    $imageUrl = $hotel->image_url ?: 'https://commons.wikimedia.org/wiki/Special:FilePath/Nine%20Arches%20Bridge%20in%20Ella.jpg';
@endphp

<main class="booking-page">
    <section class="booking-hero">
        <div class="container">
            @if(session('status'))
                <div class="alert alert-success booking-alert" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    @include('public.components.booking-summary', ['hotel' => $hotel, 'imageUrl' => $imageUrl])
                </div>
                <div class="col-lg-7">
                    @include('public.components.booking-form', ['hotel' => $hotel])
                </div>
            </div>
        </div>
    </section>
</main>

@include('public.components.footer')
@include('public.components.scripts')
