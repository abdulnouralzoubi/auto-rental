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
        dump("Lessor Dashboard Controller is working");
    }

    public function index()
    {
        $cars = Car::where('lessor_id', auth()->user()->id)->get();

        $reservations = Reservation::paginate(8);
        return view('lessor.lessorDashboard', compact('cars', 'reservations'));
    }
}
