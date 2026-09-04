@php
    $inventoryGroups = $inventories->groupBy('category');
@endphp

<div class="hotel-inventory">
    <div class="hotel-inventory__header">
        <div>
            <p class="travel-location mb-2">Package inventory</p>
            <h2 class="h5 fw-bold mb-0">Included with this stay</h2>
        </div>

        @if($inventories->isNotEmpty())
            <span>{{ $inventories->count() }} items</span>
        @endif
    </div>

    @if($inventoryGroups->isNotEmpty())
        <div class="accordion travel-faq hotel-inventory__accordion" id="hotelInventory">
            @foreach($inventoryGroups as $category => $items)
                @php
                    $collapseId = 'inventoryCategory'.$loop->index;
                @endphp

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="{{ $collapseId }}">
                            <span>{{ $category }}</span>
                            <small>{{ $items->count() }} items</small>
                        </button>
                    </h3>
                    <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#hotelInventory">
                        <div class="accordion-body">
                            <ul class="hotel-inventory__list">
                                @foreach($items as $item)
                                    <li>
                                        <div>
                                            <span>{{ $item->name }}</span>
                                            @if($item->description)
                                                <small>{{ $item->description }}</small>
                                            @endif
                                        </div>

                                        <div class="hotel-inventory__meta">
                                            @if($item->menu_type)
                                                <em>{{ $item->menu_type }}</em>
                                            @endif
                                            @if($item->price)
                                                <em>LKR {{ number_format((float) $item->price, 2) }}</em>
                                            @endif
                                            @if($item->people_count)
                                                <em>{{ $item->people_count }} people</em>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted mb-0">No food or package inventory has been added yet.</p>
    @endif
</div>
