<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function home(): View
    {
        $hotels = Hotel::all();

        return view('public.home', compact('hotels'));
    }

    public function booking(Hotel $hotel): View
    {
        return view('public.booking', compact('hotel'));
    }

    public function storeBooking(Request $request, Hotel $hotel): RedirectResponse
    {
        $hotel->bookings()->create($request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:255'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1', 'max:20'],
            'message' => ['nullable', 'string', 'max:500'],
        ]));

        return redirect()
            ->route('hotels.booking', $hotel)
            ->with('status', 'Booking request sent. We will contact you soon.');
    }
}
