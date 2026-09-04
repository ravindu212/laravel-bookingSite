<section class="admin-card h-100">
    <div class="admin-card__header mb-4">
        <div>
            <p class="admin-eyebrow mb-2">Excel import</p>
            <h2 class="h4 fw-bold mb-1">Import CSV</h2>
            <p class="text-muted mb-0">Export from Excel as CSV with columns: category, menu_type, name, description, price, people_count.</p>
        </div>
    </div>

    <form action="{{ route('admin.hotels.inventories.import', $hotel) }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <div class="col-12">
            <label for="inventory-file" class="form-label">CSV file</label>
            <input type="file" class="form-control @error('inventory_file') is-invalid @enderror" id="inventory-file" name="inventory_file" accept=".csv,text/csv" required>
            @error('inventory_file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <div class="inventory-sample">
                <strong>Example row</strong>
                <span>Foods, Lunch, Rice and curry buffet, Chicken curry with dhal and sambol, 3500, 2</span>
            </div>
        </div>

        <div class="col-12 d-grid">
            <button type="submit" class="btn btn-success btn-lg px-4">Import items</button>
        </div>
    </form>
</section>
