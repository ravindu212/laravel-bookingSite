<?php

namespace App\Livewire;

use App\Models\Review;
use Illuminate\View\View;
use Livewire\Component;

class CustomerReviews extends Component
{
    public string $customerName = '';

    public string $location = '';

    public string $rating = '';

    public string $comment = '';

    public function save(): void
    {
        $data = $this->validate([
            'customerName' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:500'],
        ]);

        Review::create([
            'customer_name' => $data['customerName'],
            'location' => $data['location'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'is_approved' => false,
        ]);

        $this->reset(['customerName', 'location', 'rating', 'comment']);

        session()->flash('review_status', 'Thank you. Your review will show after admin approval.');
    }

    public function render(): View
    {
        $reviews = Review::where('is_approved', true)->latest()->get();

        return view('livewire.customer-reviews', compact('reviews'));
    }
}
