<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Recipient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    public function index()
    {
        // makanan yang stored_at nya tidak null
        $rescuedFoods = Food::all()->whereNotNull('stored_at');

        $rescuedFoodAmount = [
            'kg' => $this->rescuedFoodAmount($rescuedFoods, 1, 'amount'),
            'porsi' => $this->rescuedFoodAmount($rescuedFoods, 2, 'amount')
        ];

        $rescuedFoodInStock = [
            'kg' => $this->rescuedFoodAmount($rescuedFoods, 1, 'in_stock'),
            'porsi' => $this->rescuedFoodAmount($rescuedFoods, 2, 'in_stock')
        ];

        $expiredThisWeek = $this->expiredThisWeek($rescuedFoods);

        $donors = User::with('roles')->get()->filter(
            fn ($user) => $user->roles->where('name', 'donor')->toArray()
        )->count();

        $volunteers = User::with('roles')->get()->filter(
            fn ($user) => $user->roles->where('name', 'volunteer')->toArray()
        )->count();

        $recipients = Recipient::where('status', 'diterima');
        $recipients = [
            'familyAmount' => $recipients->count(),
            'familyMemberAmount' => $recipients->sum('family_members')
        ];

        return view('analytic.index', [
            'rescuedFoodAmount' => $rescuedFoodAmount,
            'rescuedFoodInStock' => $rescuedFoodInStock,
            'expiredThisWeek' => $expiredThisWeek,
            'donors' => $donors,
            'volunteers' => $volunteers,
            'recipients' => $recipients
        ]);
    }

    public function show(Request $request, $category)
    {
        switch ($category) {
            case 'inventory':
                $rescuedFoods = Food::all()->whereNotNull('stored_at');

                return view('analytic.show.inventory', ['rescuedFoods' => $rescuedFoods]);
                break;
            default:
                # code...
                break;
        }
    }

    private function rescuedFoodAmount($rescuedFoods, $unit, $column)
    {
        return $rescuedFoods->filter(function ($rescuedFood) use ($unit, $column) {
            return $rescuedFood->unit_id === $unit;
        })->sum($column);
    }

    private function expiredThisWeek($rescuedFoods)
    {
        return $rescuedFoods->whereBetween('expired_date', [Carbon::now(), Carbon::now()->add(7, 'day')])->sum('in_stock');
    }
}
