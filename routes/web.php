<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CarController;
use App\Http\Controllers\LesCarController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\clientCarController;
use App\Http\Controllers\adminDashboardController;
use App\Http\Controllers\lessorDashboardController;
use App\Http\Controllers\usersController;
use App\Http\Controllers\addNewAdminController;
use App\Http\Controllers\invoiceController;
use App\Http\Controllers\AdminAuth\LoginController;
use App\Http\Controllers\carSearchController;
use App\Models\User;
use App\Models\Car;
use App\Models\Reservation;


// ------------------- guest routes --------------------------------------- //
Route::get('/', function () {
    $cars = Car::where(function ($query) {
        $today = now();
        $query->whereDoesntHave('reservations')
              ->orWhereHas('reservations', function ($query) use ($today) {
                  $query->where('start_date', '>=', $today->copy()->addHours(12))
                        ->orWhere('end_date', '<', $today);
              });
    })->take(6)->get();
    return view('home', compact('cars'));
})->name('home');

Route::get('/cars', [clientCarController::class, 'index'])->name('cars');
Route::get('/cars/search', [carSearchController::class, 'search'])->name('carSearch');

Route::get('location', function () {
    return view('location');
})->name('location');

Route::get('contact_us', function () {
    return view('contact_us');
})->name('contact_us');

Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'login'])->name('admin.login.submit');

Route::redirect('/admin', 'admin/login');
Route::redirect('/lessor', '/login');

Route::get('/privacy_policy',
function () {
    return view('Privacy_Policy');
})->name('privacy_policy');

Route::get('/terms_conditions',
function () {
    return view('Terms_Conditions');
})->name('terms_conditions');


// -------------------------------------------------------------------------//



// ------------------- admin routes --------------------------------------- //

Route::prefix('admin')->middleware('admin')->group(function () {

    Route::get(
        '/dashboard',
        adminDashboardController::class
    )->name('adminDashboard');

    Route::resource('cars', CarController::class);

    // Route::resource('reservations', ReservationController::class);
    Route::get('/users', function () {

        $admins = User::where('role', 'admin')->get();
        $lessors = User::where('role', 'lessor')->paginate(5);
        $clients = User::where('role', 'client')->paginate(5);

        return view('admin.users', compact('admins', 'lessors', 'clients'));
    })->name('users');

    Route::get('/updatePayment/{reservation}', [ReservationController::class, 'editPayment'])->name('editPayment');
    Route::put('/updatePayment/{reservation}', [ReservationController::class, 'updatePayment'])->name('updatePayment');

    Route::get('/updateReservation/{reservation}', [ReservationController::class, 'editStatus'])->name('editStatus');
    Route::put('/updateReservation/{reservation}', [ReservationController::class, 'updateStatus'])->name('updateStatus');

    Route::get('/addAdmin', [usersController::class, 'create'])->name('addAdmin');
    Route::post('/addAdmin', [addNewAdminController::class, 'register'])->name('addNewAdmin');

    // Route::delete('/deleteUser/{user}', [usersController::class, 'destroy'])->name('deleteUser');

    Route::get('/userDetails/{user}', [usersController::class, 'show'])->name('userDetails');
});

// ------------------- lessor routes --------------------------------------- //

Route::prefix('lessor')->middleware('lessor')->group(function () {
    Route::get(
        '/dashboard',
        lessorDashboardController::class
    )->name('lessorDashboard');
    Route::resource('cars', LesCarController::class)->names('lessor.cars');

    // Route::resource('reservations', ReservationController::class);

    // Common routes for reservations with lessor prefix and middleware
    Route::get('/updateReserv/{reservation}', [ReservationController::class, 'editStatus'])->name('lessor.editStatus');
    Route::put('/updateReserv/{reservation}', [ReservationController::class, 'updateStatus'])->name('lessor.updateStatus');

    // Route::delete('/deleteUser/{user}', [usersController::class, 'destroy'])->name('deleteUser');
});


// ------------------- client routes --------------------------------------- //

Route::get('/reservations/{car}', [ReservationController::class, 'create'])->name('car.reservation')->middleware('auth', 'restrictAdminAccess');
Route::post('/reservations/{car}', [ReservationController::class, 'store'])->name('car.reservationStore')->middleware('auth', 'restrictAdminAccess');

Route::get('/reservations', function () {

    $reservations = Reservation::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
    return view('clientReservations', compact('reservations'));
})->name('clientReservation')->middleware('auth', 'restrictAdminAccess');


route::get('invoice/{reservation}', [invoiceController::class, 'invoice'])->name('invoice')->middleware('auth', 'restrictAdminAccess');


//---------------------------------------------------------------------------//

Auth::routes();


// --------------------------------------------------------------------------//

