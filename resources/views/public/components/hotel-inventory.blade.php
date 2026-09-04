@php
    $inventoryGroups = $inventories->groupBy('category');
@endphp

<div class="hotel-inventory">
    <p class="travel-location mb-2">Package inventory</p>
    <h2 class="h5 fw-bold mb-3">Included with this stay</h2>

    @if($inventoryGroups->isNotEmpty())
        <div class="hotel-inventory__groups">
            @foreach($inventoryGroups as $category => $items)
                <div class="hotel-inventory__group">
                    <strong>{{ $category }}</strong>
                    <ul>
                        @foreach($items as $item)
                            <li>
                                <span>{{ $item->name }}</span>
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
                                @if($item->description)
                                    <small>{{ $item->description }}</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted mb-0">No food or package inventory has been added yet.</p>
    @endif
</div>
