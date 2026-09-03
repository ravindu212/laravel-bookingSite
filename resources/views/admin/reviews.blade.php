@include('admin.components.header')
@include('admin.components.nav')

<main class="admin-shell">
    <div class="admin-layout">
        @include('admin.components.sidebar', ['active' => 'reviews'])

        <div class="admin-main">
            @include('admin.components.page-header', [
                'title' => 'Reviews',
                'description' => 'Approve customer reviews before they appear on the public home page.',
                'actionHref' => route('home'),
                'actionLabel' => 'View public site',
            ])

            @if(session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @include('admin.components.review-table', ['reviews' => $reviews])
        </div>
    </div>
</main>

@include('admin.components.scripts')
