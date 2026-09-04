<section class="admin-card h-100">
    <div class="admin-card__header mb-4">
        <div>
            <p class="admin-eyebrow mb-2">Manual inventory</p>
            <h2 class="h4 fw-bold mb-1">Add one item</h2>
            <p class="text-muted mb-0">Use this for meal menus, package prices, transport, pool, games, or anything included with the stay.</p>
        </div>
    </div>

    <form action="{{ route('admin.hotels.inventories.store', $hotel) }}" method="POST" class="row g-3">
        @csrf

        <div class="col-md-4">
            <label for="category" class="form-label">Category</label>
            <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                <option value="">Choose category</option>
                @foreach(['Foods', 'Package', 'Entertainment', 'Transport', 'Other'] as $category)
                    <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                @endforeach
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="menu-type" class="form-label">Type</label>
            <select class="form-control @error('menu_type') is-invalid @enderror" id="menu-type" name="menu_type">
                <option value="">No type</option>
                @foreach(['Breakfast', 'Lunch', 'Dinner', 'Package', 'Pool', 'Games', 'Tour'] as $type)
                    <option value="{{ $type }}" @selected(old('menu_type') === $type)>{{ $type }}</option>
                @endforeach
            </select>
            @error('menu_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="inventory-name" class="form-label">Item name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="inventory-name" name="name" value="{{ old('name') }}" placeholder="Rice and curry buffet" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="inventory-description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="inventory-description" name="description" rows="4" placeholder="String hoppers, dhal curry, coconut sambol.">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="price" class="form-label">Price LKR</label>
            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" placeholder="3500">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="people-count" class="form-label">For how many people</label>
            <input type="number" class="form-control @error('people_count') is-invalid @enderror" id="people-count" name="people_count" value="{{ old('people_count') }}" min="1" max="100" placeholder="2">
            @error('people_count')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 d-grid d-sm-flex justify-content-sm-end">
            <button type="submit" class="btn btn-success btn-lg px-4">Add item</button>
        </div>
    </form>
</section>
