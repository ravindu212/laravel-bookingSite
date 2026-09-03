<section class="admin-card">
    <div class="admin-card__header mb-4">
        <div>
            <p class="admin-eyebrow mb-2">Customer reviews</p>
            <h2 class="h4 fw-bold mb-1">Review approvals</h2>
            <p class="text-muted mb-0">Only approved reviews appear on the public site.</p>
        </div>
    </div>

    @if($reviews->isNotEmpty())
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                        <tr>
                            <td>
                                <strong>{{ $review->customer_name }}</strong>
                                <small>{{ $review->location ?: 'No location' }}</small>
                            </td>
                            <td>{{ $review->rating }} stars</td>
                            <td>{{ $review->comment }}</td>
                            <td>{{ $review->is_approved ? 'Approved' : 'Pending' }}</td>
                            <td>
                                <div class="admin-table__actions">
                                    @if($review->is_approved)
                                        <span class="text-muted">Done</span>
                                    @else
                                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="admin-empty">
            <strong>No reviews yet</strong>
            <p class="mb-0">Customer reviews will appear here after visitors submit them.</p>
        </div>
    @endif
</section>
