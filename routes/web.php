<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RescueController;
use App\Models\Category;
use App\Models\Food;
use App\Models\FoodRescue;
use App\Models\FoodVault;
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
    $rescue = Rescue::where('id', $id)->first();
    $foodCategories = Category::all();
    $foodSubCategories = SubCategory::all();
    return view('donors.rescues.foods.create', ['rescue' => $rescue, 'foodCategories' => $foodCategories, 'foodSubCategories' => $foodSubCategories]);
})->middleware('auth', 'donor')->name('donors.rescues.foods.create');

Route::post('/donors/rescues/{id}/foods/store', function (Request $request, string $id) {

    $photo = $request->file('photo')->store('rescue-documentations');

    // format date
    $expired_date = explode('/', $request->expired_date);
    $month = $expired_date[0];
    $day = $expired_date[1];
    $year = $expired_date[2];
    $expired_date = Carbon::create($year, $month, $day, 0, 0, 0, 'UTC');

    $food = new Food();
    $food->name = $request->name;
    $food->detail = $request->detail;
    $food->expired_date = $expired_date;
    $food->amount = $request->amount;
    $food->unit = $request->unit;
    $food->photo = $photo;
    $food->user_id = $request->user_id;
    $food->category_id = $request->category;
    $food->sub_category_id = $request->sub_category;
    $food->save();

    $foodRescue = new FoodRescue();
    $foodRescue->food_id = $food->id;
    $foodRescue->rescue_id = $id;
    $foodRescue->save();

    // save rescue photo logs
    $rescue = Rescue::where('id', $id)->first();
    $rescueUserID = $rescue->user_logs->first()->pivot->id;
    $userID = auth()->user()->id;

    $rescuePhoto = new RescuePhoto();
    $rescuePhoto->photo = $photo;
    $rescuePhoto->rescue_user_id = $rescueUserID;
    $rescuePhoto->user_id = $userID;
    $rescuePhoto->save();

    // save to vaults
    $foodVault = new FoodVault();
    $foodVault->food_id = $food->id;
    $foodVault->vault_id = 1;
    $foodVault->save();

    return redirect()->route('donors.rescues.show', ["id" => $id]);
})->middleware('auth', 'donor')->name('donors.rescues.foods.store');

// volunteer
Route::get('/volunteer/rescues', function (Request $request) {
    $rescues = Rescue::all();
    $param_status = $request->query("status");
    $rescues = Rescue::where('status', $param_status)->get();
    return view('volunteer.dashboard', ['rescues' => $rescues, 'active' => $param_status]);
})->middleware('auth', 'volunteer')->name('volunteer.dashboard');

Route::get('/volunteer/rescues/{id}/edit', function (Request $request, string $id) {
    $rescue = Rescue::where('id', $id)->get()->first();

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

    return view('volunteer.rescues.show', ["rescue" => $rescue]);
})->middleware('auth', 'volunteer')->name('volunteer.rescues.edit');

Route::get('/volunteer/rescues/{rescueID}/foods/{foodID}/edit', function (string $rescueID, string $foodID) {
    $food = Food::where('id', $foodID)->get()->first();

    // date
    $expired_date = explode(' ', $food->expired_date);
    $expired_date = explode(' ', $expired_date[0])[0];
    $date = explode('-', $expired_date);
    $year = $date[0];
    $month = $date[1];
    $day = $date[2];
    $food->expired_date = "$month/$day/$year";

    // get photo timeline for foods
    $rescue = Rescue::find($rescueID);
    $rescueUserIDs = $rescue->user_logs->map(function ($user_log) {
        return $user_log->pivot->id;
    });

    $rescuePhotos = collect([]);
    foreach ($rescueUserIDs as $rescueUserID) {
        $rescuePhoto = RescuePhoto::where('rescue_user_id', $rescueUserID)->get();
        if (!$rescuePhoto->isEmpty()) {
            $rescuePhotos->push($rescuePhoto);
        }
    }

    return view('volunteer.rescues.foods.show', ["food" => $food, "rescuePhotos" => $rescuePhotos, "rescue" => $rescue]);
})->middleware('auth', 'volunteer')->name('volunteer.rescues.foods.edit');

Route::put('/volunteer/rescues/{rescueID}/foods/{foodID}', function (Request $request, string $rescueID, string $foodID) {

    $photo = $request->file('photo')->store('rescue-documentations');

    // format date
    $expired_date = explode('/', $request->expired_date);
    $month = $expired_date[0];
    $day = $expired_date[1];
    $year = $expired_date[2];
    $expired_date = Carbon::create($year, $month, $day, 0, 0, 0, 'UTC');

    // update food data
    $food = Food::find($foodID);
    $food->amount = $request->amount;
    $food->expired_date = $expired_date;
    $food->save();

    // save rescue photo logs
    $rescue = Rescue::where('id', $rescueID)->first();
    $rescueUserIDs = $rescue->user_logs;
    $rescueUserID = null;

    foreach ($rescueUserIDs as $rescueUserID) {
        if ($rescueUserID->pivot->status === $request->status) {
            $rescueUserID = $rescueUserID->pivot->id;
        }
    }

    $userID = auth()->user()->id;

    $rescuePhoto = new RescuePhoto();
    $rescuePhoto->photo = $photo;
    $rescuePhoto->rescue_user_id = $rescueUserID;
    $rescuePhoto->user_id = $userID;
    $rescuePhoto->save();
})->middleware('auth', 'volunteer')->name('volunteer.rescues.foods.update');


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
