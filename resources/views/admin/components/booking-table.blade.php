<section class="admin-card">
    <div class="admin-card__header mb-4">
        <div>
            <p class="admin-eyebrow mb-2">Booking requests</p>
            <h2 class="h4 fw-bold mb-1">Latest customer requests</h2>
            <p class="text-muted mb-0">Simple request inbox for the stays on your public site.</p>
        </div>
    </div>

    @if($bookings->isNotEmpty())
        <div class="table-responsive">
            <table class="table admin-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Stay</th>
                        <th>Guest</th>
                        <th>Dates</th>
                        <th>Guests</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>
                                <strong>{{ $booking->hotel?->name ?: 'Deleted stay' }}</strong>
                                <small>{{ $booking->hotel?->location ?: 'No location' }}</small>
                            </td>
                            <td>
                                <strong>{{ $booking->customer_name }}</strong>
                                <small>{{ $booking->customer_email }}</small>
                                <small>{{ $booking->customer_phone ?: 'No phone' }}</small>
                            </td>
                            <td>
                                <span>{{ $booking->check_in->format('M d, Y') }}</span>
                                <small>to {{ $booking->check_out->format('M d, Y') }}</small>
                            </td>
                            <td>{{ $booking->guests }}</td>
                            <td>{{ $booking->message ?: 'No message' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="admin-empty">
            <strong>No booking requests yet</strong>
            <p class="mb-0">Requests sent from hotel booking pages will appear here.</p>
        </div>
    @endif
</section>
