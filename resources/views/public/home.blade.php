@include('public.components.header')
@include('public.components.nav')

<section class="travel-hero text-white">
    <div class="travel-hero__overlay"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-7">
                <span class="travel-eyebrow">Sri Lanka travel stays</span>
                <h1 class="display-3 fw-bold mb-4">Plan a bright little island escape.</h1>
                <p class="lead mb-4">
                    Explore beach towns, misty tea country, ancient kingdoms, and boutique stays across Sri Lanka.
                    Built for practice, styled like it knows where the good hoppers are.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#stays" class="btn btn-warning btn-lg fw-bold px-4">Explore stays</a>
                    <a href="{{ route('admin.login') }}" class="btn btn-outline-light btn-lg px-4">Open admin</a>
                </div>
            </div>
            <div class="col-lg-4 offset-lg-1 mt-5 mt-lg-0">
                <div class="travel-search-panel">
                    <p class="text-uppercase small fw-bold text-muted mb-3">Quick finder</p>
                    <form class="d-grid gap-3">
                        <div>
                            <label class="form-label">Destination</label>
                            <input type="text" class="form-control form-control-lg" value="Ella, Sri Lanka">
                        </div>
                        <div>
                            <label class="form-label">Travel style</label>
                            <select class="form-select form-select-lg">
                                <option selected>Hill country</option>
                                <option>Beach weekend</option>
                                <option>Heritage tour</option>
                                <option>Wildlife safari</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg fw-bold">Search trips</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
    <section class="travel-section">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="travel-stat">
                        <span>09</span>
                        <p>Provinces to explore</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="travel-stat">
                        <span>26 C</span>
                        <p>Coastal mood, usually</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="travel-stat">
                        <span>3h</span>
                        <p>Colombo to Galle by road</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('public.components.experiences')

    <section id="stays" class="travel-section travel-section--soft">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-end mb-4">
                <div>
                    <span class="travel-eyebrow travel-eyebrow--dark">Featured stays</span>
                    <h2 class="fw-bold mb-0">Sri Lankan places worth saving</h2>
                </div>
            </div>

            @if($hotels->isNotEmpty())
                <div class="row g-4">
                    @foreach($hotels as $hotel)
                        <div class="col-md-6 col-lg-4">
                            @include('public.components.hotel-card', ['hotel' => $hotel])
                        </div>
                    @endforeach
                </div>
            @else
                <div class="travel-empty">
                    <h3 class="h5 fw-bold">No stays yet</h3>
                    <p class="text-muted mb-3">Create your first stay from the admin dashboard and it will appear here.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-success">Add stay</a>
                </div>
            @endif
        </div>
    </section>

    <section class="travel-section">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5">
                    <span class="travel-eyebrow travel-eyebrow--dark">Weekend idea</span>
                    <h2 class="fw-bold">Colombo to Galle, then Mirissa by sunset.</h2>
                    <p class="text-muted mb-0">
                        Start with fort streets and sea breeze, stop for seafood, then roll down the coast for coconut palms and orange skies.
                    </p>
                </div>
                <div class="col-lg-7">
                    <div class="travel-route">
                        <span>Colombo</span>
                        <span>Galle Fort</span>
                        <span>Mirissa</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@include('public.components.footer')
@include('public.components.scripts')
