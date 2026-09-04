<section class="travel-section travel-reviews" id="reviews">
    <div class="container">
        <div class="travel-reviews__header">
            <span class="travel-eyebrow travel-eyebrow--dark">Customer reviews</span>
            <h2 class="fw-bold mb-3">What travellers say</h2>
            <p class="text-muted mb-0">
                Share a quick note about your Sri Lankan trip. Reviews appear here after admin approval.
            </p>
        </div>

        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5">
                <form class="travel-review-form" action="{{ route('reviews.store') }}" method="POST" wire:submit="save">
                    @csrf

                    <div class="travel-review-form__intro">
                        <strong>Leave a review</strong>
                        <span>Your message will be checked before it appears publicly.</span>
                    </div>

                    @if(session('review_status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('review_status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            Please check your review details.
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="customerName" class="form-label">Name</label>
                            <input id="customerName" name="customer_name" wire:model="customerName" class="form-control @error('customerName') is-invalid @enderror @error('customer_name') is-invalid @enderror" placeholder="Your name" required>
                            @error('customerName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="reviewLocation" class="form-label">Location</label>
                            <input id="reviewLocation" name="location" wire:model="location" class="form-control @error('location') is-invalid @enderror" placeholder="Kandy, Sri Lanka">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="rating" class="form-label">Rating</label>
                            <select id="rating" name="rating" wire:model="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                <option value="">Choose rating</option>
                                @for($ratingOption = 5; $ratingOption >= 1; $ratingOption--)
                                    <option value="{{ $ratingOption }}">{{ $ratingOption }} stars</option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="comment" class="form-label">Review</label>
                            <textarea id="comment" name="comment" wire:model="comment" class="form-control @error('comment') is-invalid @enderror" rows="4" placeholder="Tell us about the trip." required></textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-4">
                        <span wire:loading.remove wire:target="save">Add review</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </form>
            </div>

            <div class="col-lg-7">
                @if($reviews->isNotEmpty())
                    <div class="travel-review-list">
                        @foreach($reviews as $review)
                            <article class="travel-review" wire:key="review-{{ $review->id }}">
                                <div class="travel-review__top">
                                    <div class="travel-review__avatar">
                                        {{ str($review->customer_name)->substr(0, 1)->upper() }}
                                    </div>
                                    <div>
                                        <h3 class="h5 fw-bold mb-1">{{ $review->customer_name }}</h3>
                                        <p class="travel-location mb-0">{{ $review->location ?: 'Sri Lanka traveller' }}</p>
                                    </div>
                                </div>
                                <div class="travel-review__stars" aria-label="{{ $review->rating }} star review">
                                    @for($star = 0; $star < $review->rating; $star++)
                                        &#9733;
                                    @endfor
                                </div>
                                <p class="text-muted mb-0">{{ $review->comment }}</p>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="travel-review-empty">
                        <div>
                            <span>5</span>
                            <small>stars waiting</small>
                        </div>
                        <h3 class="h4 fw-bold">No approved reviews yet</h3>
                        <p class="text-muted mb-0">
                            When a traveller submits a review, it waits in the admin dashboard. Once approved, it will appear here.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
