<?php

use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FoodDonationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RescueController;
use App\Http\Controllers\UserController;
use App\Models\Category;
use App\Models\Donation;
use App\Models\DonationFood;
use App\Models\DonationPhoto;
use App\Models\Food;
use App\Models\FoodRescue;
use App\Models\FoodVault;
use App\Models\Recipient;
use Illuminate\Support\Facades\Route;
use App\Models\Rescue;
use App\Models\RescuePhoto;
use App\Models\RescueUser;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

Route::resource('rescues', RescueController::class);
Route::put('/rescues/{rescue}/status', [RescueController::class, 'updateStatus'])->name('rescues.update.status');
Route::resource('rescues.foods', FoodController::class);
Route::resource('donations', DonationController::class);
Route::resource('donations.foods', FoodDonationController::class);
Route::resource('reports', ReportController::class);
Route::resource('foods', FoodController::class);
Route::resource('recipients', RecipientController::class);
Route::resource('users', UserController::class)->only('show');

Route::get('/analytics', [AnalyticController::class, 'index'])->name('analytics.index');
Route::get('/analytics/{category}', [AnalyticController::class, 'show'])->name('analytics.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
