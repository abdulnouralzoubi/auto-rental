<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Reservation;

class lessorDashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $cars = Car::all();

        $reservations = Reservation::paginate(8);
        return view('lessor.lessorDashboard', compact('cars', 'reservations'));
    }
}
