<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelInventory;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function reviews(): View
    {
        $reviews = Review::latest()->get();

        return view('admin.reviews', compact('reviews'));
    }

    public function approveReview(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => true]);

        return redirect()
            ->route('admin.reviews.index')
            ->with('status', 'Review approved.');
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

    public function hotelInventories(Hotel $hotel): View
    {
        $hotel->load('inventories');

        return view('admin.hotel-inventories', compact('hotel'));
    }

    public function storeHotelInventory(Request $request, Hotel $hotel): RedirectResponse
    {
        $hotel->inventories()->create($this->validateInventory($request));

        return redirect()
            ->route('admin.hotels.inventories', $hotel)
            ->with('status', 'Inventory item added.');
    }

    public function importHotelInventories(Request $request, Hotel $hotel): RedirectResponse
    {
        $request->validate([
            'inventory_file' => ['required', 'file', 'extensions:csv,txt', 'max:2048'],
        ]);

        $items = $this->readInventoryCsv($request->file('inventory_file'));

        foreach ($items as $item) {
            $hotel->inventories()->create($item);
        }

        return redirect()
            ->route('admin.hotels.inventories', $hotel)
            ->with('status', count($items).' inventory items imported.');
    }

    public function exportHotelInventories(Hotel $hotel): StreamedResponse
    {
        $filename = str($hotel->name)->slug().'-inventories.csv';

        return response()->streamDownload(function () use ($hotel): void {
            $file = fopen('php://output', 'w');

            if ($file === false) {
                return;
            }

            fputcsv($file, ['category', 'menu_type', 'name', 'description', 'price', 'people_count']);

            foreach ($hotel->inventories()->orderBy('category')->orderBy('name')->get() as $inventory) {
                fputcsv($file, [
                    $inventory->category,
                    $inventory->menu_type,
                    $inventory->name,
                    $inventory->description,
                    $inventory->price,
                    $inventory->people_count,
                ]);
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function editHotelInventory(Hotel $hotel, HotelInventory $inventory): View
    {
        abort_unless($inventory->hotel_id === $hotel->id, 404);

        return view('admin.edit-inventory', compact('hotel', 'inventory'));
    }

    public function updateHotelInventory(Request $request, Hotel $hotel, HotelInventory $inventory): RedirectResponse
    {
        abort_unless($inventory->hotel_id === $hotel->id, 404);

        $inventory->update($this->validateInventory($request));

        return redirect()
            ->route('admin.hotels.inventories', $hotel)
            ->with('status', 'Inventory item updated.');
    }

    public function destroyHotelInventory(Hotel $hotel, HotelInventory $inventory): RedirectResponse
    {
        abort_unless($inventory->hotel_id === $hotel->id, 404);

        $inventory->delete();

        return redirect()
            ->route('admin.hotels.inventories', $hotel)
            ->with('status', 'Inventory item deleted.');
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

    /**
     * @return array{category: string, menu_type?: string|null, name: string, description?: string|null, price?: numeric-string|null, people_count?: int|null}
     */
    private function validateInventory(Request $request): array
    {
        return $request->validate([
            'category' => ['required', 'string', 'max:255'],
            'menu_type' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'people_count' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);
    }

    /**
     * @return array<int, array{category: string, menu_type?: string|null, name: string, description?: string|null, price?: float|null, people_count?: int|null}>
     */
    private function readInventoryCsv(?UploadedFile $file): array
    {
        if ($file === null) {
            return [];
        }

        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return [];
        }

        $headers = fgetcsv($handle) ?: [];
        $headers = array_map(function (string $header): string {
            return strtolower(trim($header, " \t\n\r\0\x0B\xEF\xBB\xBF"));
        }, $headers);
        $items = [];

        while (($row = fgetcsv($handle)) !== false) {
            $row = array_slice(array_pad($row, count($headers), ''), 0, count($headers));
            $data = array_combine($headers, $row);

            if ($data === false) {
                continue;
            }

            $category = trim((string) ($data['category'] ?? ''));
            $menuType = trim((string) ($data['menu_type'] ?? ''));
            $name = trim((string) ($data['name'] ?? ''));
            $description = trim((string) ($data['description'] ?? ''));
            $price = trim((string) ($data['price'] ?? ''));
            $peopleCount = trim((string) ($data['people_count'] ?? ''));

            if ($category === '' || $name === '') {
                continue;
            }

            $items[] = [
                'category' => $category,
                'menu_type' => $menuType !== '' ? $menuType : null,
                'name' => $name,
                'description' => $description !== '' ? $description : null,
                'price' => is_numeric($price) ? (float) $price : null,
                'people_count' => ctype_digit($peopleCount) ? (int) $peopleCount : null,
            ];
        }

        fclose($handle);

        return $items;
    }
}
