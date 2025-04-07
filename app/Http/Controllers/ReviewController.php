<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $reviews = Review::all();
    //     return view('reviews.index', compact('reviews'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('review.create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $hasReservation = Reservation::where('car_id', $request->car_id)
                                      ->where('user_id', $user->id)
                                      ->exists();

        if (!$hasReservation) {
            return redirect()->back()->with('error', 'You must have a reservation for this car to leave a review.');
        }

        $existingReview = Review::where('car_id', $request->car_id)
                                ->where('user_id', $user->id)
                                ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You can only leave one review per car.');
        }

        Review::create([
            'user_id' => $user->id,
            'car_id' => $request->car_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('car.reservation', ['car' => $request->car_id])->with('success', 'Review added successfully.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Review $review)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Review $review)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to edit this review.');
        }

        $review->update($request->all());

        return redirect()->route('car.reservation', ['car' => $review->car_id])->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this review.');
        }

        $review->delete();

        return redirect()->route('car.reservation', ['car' => $review->car_id])->with('success', 'Review deleted successfully.');
    }
}
