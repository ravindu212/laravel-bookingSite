<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $hotels = Hotel::latest()->get();

        return view('admin.dashboard', compact('hotels'));
    }

    public function bookings(): View
    {
        $bookings = Booking::with('hotel')->latest()->get();

        return view('admin.bookings', compact('bookings'));
    }

    public function storeHotel(Request $request): RedirectResponse
    {
        Hotel::create($this->validateHotel($request));

        return redirect()
            ->route('dashboard')
            ->with('status', 'Stay saved. It is now showing on the public site.');
    }

    public function editHotel(Hotel $hotel): View
    {
        return view('admin.edit-hotel', compact('hotel'));
    }

    public function updateHotel(Request $request, Hotel $hotel): RedirectResponse
    {
        $hotel->update($this->validateHotel($request));

        return redirect()
            ->route('dashboard')
            ->with('status', 'Stay updated.');
    }

    public function destroyHotel(Hotel $hotel): RedirectResponse
    {
        $hotel->delete();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Stay deleted.');
    }

    /**
     * @return array{name: string, description?: string|null, image_url?: string|null, location?: string|null, phone?: string|null, email?: string|null, website?: string|null}
     */
    private function validateHotel(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'image_url' => ['nullable', 'url', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
        ]);
    }
}
