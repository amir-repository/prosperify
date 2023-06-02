<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RescueController;
use Illuminate\Support\Facades\Route;
use App\Models\Rescue;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// donor
Route::get('donors/rescues', function () {
    $rescues = User::where('id', auth()->user()->id)->get()[0]->rescues;
    return view('donors.dashboard', ['rescues' => $rescues]);
})->middleware('auth', 'donor')->name('donors.dashboard');

Route::get('donors/rescues/{id}', function (string $id) {
    $rescue = Rescue::where('id', $id)->get()[0];

    // date
    $rescue_datetime = explode(' ', $rescue->rescue_date);
    $rescue_datetime_date = explode(' ', $rescue_datetime[0])[0];
    $date = explode('-', $rescue_datetime_date);
    $year = $date[0];
    $month = $date[1];
    $day = $date[2];
    $rescue->rescue_date = "$month/$day/$year";

    $rescue_datetime_date = explode(':', $rescue_datetime[1]);
    $rescue->rescue_hours = $rescue_datetime_date[0];
    return  view('donors.rescues.show', ["rescue" => $rescue, 'user' => auth()->user()]);
})->middleware('auth', 'donor')->name('donors.rescues.show');

Route::get('/donors/rescues/{id}/foods/create', function (string $id) {
    $rescue_id = $id;
    return view('donors.rescues.foods.create', ['rescue_id' => $rescue_id]);
});

// volunteer
Route::get('/volunteer', function () {
    return view('volunteer.dashboard');
})->middleware('auth', 'volunteer')->name('volunteer.dashboard');


// admin
Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware('auth', 'admin')->name('admin.dashboard');

// Rescue
Route::resource('rescues', RescueController::class);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
