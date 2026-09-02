@php
    $hotel = $hotel ?? null;
    $method = $method ?? 'POST';
    $eyebrow = $eyebrow ?? 'Create stay';
    $submitLabel = $submitLabel ?? 'Save to public site';
    $showCancel = $showCancel ?? false;
@endphp

<section class="admin-card" id="create-stay">
    <div class="admin-card__header mb-4">
        <div>
            <p class="admin-eyebrow mb-2">{{ $eyebrow }}</p>
            <h2 class="h4 fw-bold mb-1">{{ $formTitle }}</h2>
            <p class="text-muted mb-0">{{ $formDescription }}</p>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ $action }}" method="POST" class="row g-3">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <div class="col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', data_get($hotel, 'name')) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', data_get($hotel, 'location')) }}" placeholder="Ella, Sri Lanka">
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', data_get($hotel, 'description')) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="image_url" class="form-label">Image URL</label>
            <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url', data_get($hotel, 'image_url')) }}" placeholder="https://commons.wikimedia.org/wiki/Special:FilePath/...">
            @error('image_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', data_get($hotel, 'phone')) }}" placeholder="+94 77 123 4567">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', data_get($hotel, 'email')) }}" placeholder="hello@example.test">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="website" class="form-label">Website</label>
            <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', data_get($hotel, 'website')) }}" placeholder="https://www.srilanka.travel/">
            @error('website')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 d-grid d-sm-flex justify-content-sm-end gap-2">
            @if($showCancel)
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg px-4">Cancel</a>
            @endif
            <button type="submit" class="btn btn-success btn-lg px-4">{{ $submitLabel }}</button>
        </div>
    </form>
</section>
