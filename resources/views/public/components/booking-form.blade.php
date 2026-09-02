<form class="booking-form" action="{{ route('hotels.booking.store', $hotel) }}" method="POST">
    @csrf

    <p class="travel-eyebrow travel-eyebrow--dark mb-3">Request a stay</p>
    <h2 class="h3 fw-bold mb-2">Send booking request</h2>
    <p class="text-muted mb-4">Share your dates and contact details. The admin can view this request from the dashboard.</p>

    @if($errors->any())
        <div class="alert alert-danger">
            Please check the form and try again.
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label for="customer_name" class="form-label">Name</label>
            <input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" class="form-control @error('customer_name') is-invalid @enderror" required>
            @error('customer_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="customer_email" class="form-label">Email</label>
            <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}" class="form-control @error('customer_email') is-invalid @enderror" required>
            @error('customer_email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="customer_phone" class="form-label">Phone</label>
            <input id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" class="form-control @error('customer_phone') is-invalid @enderror" placeholder="+94 77 123 4567">
            @error('customer_phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="guests" class="form-label">Guests</label>
            <input id="guests" name="guests" type="number" value="{{ old('guests', 2) }}" class="form-control @error('guests') is-invalid @enderror" min="1" max="20" required>
            @error('guests')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="check_in" class="form-label">Check in</label>
            <input id="check_in" name="check_in" type="date" value="{{ old('check_in') }}" class="form-control @error('check_in') is-invalid @enderror" required>
            @error('check_in')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="check_out" class="form-label">Check out</label>
            <input id="check_out" name="check_out" type="date" value="{{ old('check_out') }}" class="form-control @error('check_out') is-invalid @enderror" required>
            @error('check_out')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="message" class="form-label">Message</label>
            <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="4" placeholder="Tell the hotel anything important.">{{ old('message') }}</textarea>
            @error('message')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-success btn-lg mt-4">Send booking request</button>
</form>
