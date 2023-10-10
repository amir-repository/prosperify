<?php

use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FoodDonationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RescueController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VaultController;
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

Route::get('/rescues/{rescue}/foods/{food}/assignment', [FoodController::class, 'assignment'])->name('rescues.foods.assignment');

Route::post('/rescues/{rescue}/foods/{food}/assignment', [FoodController::class, 'createAssignment'])->name('rescues.foods.assignment');

Route::get('/rescues/{rescue}/foods/{food}/history', [RescueController::class, 'history'])->name('foods.rescues.history');

Route::get('/rescues/{rescue}/foods/{food}/taken-receipt/{id}', [FoodController::class, 'takenReceipt'])->name('rescues.foods.takenreceipt');

Route::get('/rescues/{rescue}/foods/{food}/stored-receipt/{id}', [FoodController::class, 'storedReceipt'])->name('rescues.foods.storedreceipt');

Route::resource('rescues.foods', FoodController::class);
Route::resource('reports', ReportController::class);
Route::resource('foods', FoodController::class);
Route::resource('recipients', RecipientController::class);
Route::resource('users', UserController::class)->only('show');

Route::resource('donations', DonationController::class);

Route::put('/donations/{donation}/status', [DonationController::class, 'updateStatus'])->name('donations.update.status');

Route::get('/donations/{donation}/foods/{food}/history', [FoodDonationController::class, 'history'])->name('donations.foods.history');

Route::resource('donations.foods', FoodDonationController::class);

Route::resource('categories', CategoryController::class)->middleware(['auth']);
Route::resource('subcategory', SubCategoryController::class)->middleware(['auth']);
Route::resource('vaults', VaultController::class)->middleware(['auth']);

Route::get('/analytics', [AnalyticController::class, 'index'])->name('analytics.index');
Route::get('/analytics/{category}', [AnalyticController::class, 'show'])->name('analytics.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
