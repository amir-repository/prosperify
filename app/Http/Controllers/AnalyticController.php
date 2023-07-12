<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Recipient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\String_;

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

        $expiredThisWeek = $this->expiredThisWeek($rescuedFoods)->sum('in_stock');

        $donors = $this->usersByRole('donor')->count();
        $volunteers = $this->usersByRole('volunteer')->count();

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
        $rescuedFoods = Food::all()->whereNotNull('stored_at');

        switch ($category) {
            case 'inventory':
                $rescuedFoods = $rescuedFoods->filter(function ($food) {
                    return $food->in_stock > 0;
                });
                return view('analytic.show.inventory', ['rescuedFoods' => $rescuedFoods, 'header' => 'Inventori']);
                break;
            case 'rescued':
                return view('analytic.show.inventory', ['rescuedFoods' => $rescuedFoods, 'header' => 'Pangan terselamatkan']);
                break;
            case 'expiring':
                $rescuedFoods = $this->expiredThisWeek($rescuedFoods);
                return view('analytic.show.inventory', ['rescuedFoods' => $rescuedFoods, 'header' => 'Kadaluarsa minggu ini']);
                break;
            case 'donors':
                $users = $this->usersByRole('donor');
                return view('analytic.show.users', ['users' => $users]);
                break;
            case 'volunteers':
                $users = $this->usersByRole('volunteer');
                return view('analytic.show.users', ['users' => $users]);
                break;
            case 'recipients':
                $recipients = Recipient::where('status', 'diterima')->get();
                return view('analytic.show.recipients', ['recipients' => $recipients]);
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
        return $rescuedFoods->whereBetween('expired_date', [Carbon::now(), Carbon::now()->add(7, 'day')]);
    }

    private function usersByRole($role)
    {
        return User::with('roles')->get()->filter(
            fn ($user) => $user->roles->where('name', $role)->toArray()
        );
    }
}
