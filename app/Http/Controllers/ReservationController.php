<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use LDAP\Result;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($car_id)
    {
        $user = auth()->user();
        $car = Car::find($car_id);
        $car_reservation = Reservation::where('car_id', $car_id)->get();
        $reviews = Review::where('car_id', $car_id)->with('user')->get();
        $userReview = Review::where('car_id', $car_id)->where('user_id', $user->id)->first();
        $canReview = Reservation::where('car_id', $car_id)
                                ->where('user_id', $user->id)
                                ->exists() && !$userReview;

        return view('reservation.create', compact('car', 'user', 'car_reservation', 'reviews', 'canReview', 'userReview'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $car_id)
    {
        // dd($request->all());
        $request->validate([
            'full-name' => 'required|string|max:255',
            'email' => 'required|email',
            'reservation_dates' => 'required',
        ]);


        $car = Car::find($car_id);
        $user = Auth::user();

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        // Check if the user has more than 2 reservations
        // $userReservationsCount = Reservation::where('user_id', $user->id)->count();
        // if ($userReservationsCount >= 2) {
        //     return redirect()->back()->with('error', 'You cannot have more than 2 active reservations.');
        // }

        // extract start and end date from the request
        $reservation_dates = explode(' to ', $request->reservation_dates);
        $start = Carbon::parse($reservation_dates[0]);
        $end = Carbon::parse($reservation_dates[1]);

        $reservation = new Reservation();
        $reservation->user()->associate($user);
        $reservation->car()->associate($car);
        $reservation->start_date = $start;
        $reservation->end_date = $end;
        $reservation->days = $start->diffInDays($end);
        $reservation->price_per_day = $car->price_per_day;
        $reservation->total_price = $reservation->days * $reservation->price_per_day;
        $reservation->status = 'Pending';
        $reservation->payment_method = 'At store';
        $reservation->payment_status = 'Pending';
        $reservation->save();

        // $car->status = 'Reserved';
        $car->save();

        return view('thankyou',['reservation'=>$reservation] );
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    // Edit and Update Payment status
    public function editPayment(Reservation $reservation)
    {
        $reservation = Reservation::find($reservation->id);
        return view('admin.updatePayment', compact('reservation'));
    }

    public function updatePayment(Reservation $reservation, Request $request)
    {
        $reservation = Reservation::find($reservation->id);
        $reservation->payment_status = $request->payment_status;
        $reservation->save();
        return redirect()->route('adminDashboard');
    }

    // Edit and Update Reservation Status
    public function editStatus(Reservation $reservation)
    {
        $reservation = Reservation::find($reservation->id);

        if (auth()->user()->role === 'admin') {
            return view('admin.updateStatus', compact('reservation'));
        } else {
            return view('lessor.updateStatus', compact('reservation'));
        }
    }

    public function updateStatus(Reservation $reservation, Request $request)
    {
        $reservation = Reservation::find($reservation->id);
        $reservation->status = $request->status;
        $car = $reservation->car;
        // if($request->status == 'Ended' || $request->status == 'Canceled' ){
        //     $car->status = 'Available';
        //     $car->save();
        // }
        $reservation->save();

        if (auth()->user()->role === 'admin') {
            return redirect()->route('adminDashboard');
        } else {
            return redirect()->route('lessorDashboard');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
