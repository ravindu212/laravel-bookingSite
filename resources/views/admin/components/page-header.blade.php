<div class="admin-hero mb-4">
    <div>
        <p class="admin-eyebrow mb-2">Travel admin</p>
        <h1 class="fw-bold mb-2">{{ $title }}</h1>
        @if(!empty($description))
            <p class="text-muted mb-0">{{ $description }}</p>
        @endif
    </div>

    @if(!empty($actionHref) && !empty($actionLabel))
        <a href="{{ $actionHref }}" class="btn btn-outline-success">{{ $actionLabel }}</a>
    @endif
</div>
