<?php

namespace App\Http\Requests;

use App\Models\Rescue;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreRescueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User */
        $user = auth()->user();
        return $user->hasAnyRole(['donor', 'admin']);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // validate if there's an active donation
        $rescues = Rescue::where('rescue_status_id', Rescue::PLANNED)
            ->orWhere('rescue_status_id', Rescue::SUBMITTED)
            ->orWhere('rescue_status_id', Rescue::PROCESSED)
            ->orWhere('rescue_status_id', Rescue::ASSIGNED)
            ->orWhere('rescue_status_id', Rescue::INCOMPLETED)->get();

        foreach ($rescues as $rescue) {
            $dbRescueDate = Carbon::parse($rescue->rescue_date);
            // rescue time is 4 hour
            $dbEndRescueDate = Carbon::parse($rescue->rescue_date)->addHours(4);
            $reqRescueDate = Carbon::parse($this->rescue_date);

            $conflictDonation = $reqRescueDate->between($dbRescueDate, $dbEndRescueDate);

            if ($conflictDonation) {
                // return 
                dd($conflictDonation, "Conflicting with $rescue->title, start: $dbRescueDate, finish: $dbEndRescueDate. Try another date time");
            }
        }

        return [
            'title' => 'required|max:100',
            'description' => 'required|max:255',
            'pickup_address' => 'required|max:255',
            'rescue_date' => 'required|after:' . $this->allowedDays(3),
            'donor_name' => 'required|max:100',
            'phone' => 'required|max:15',
            'email' => 'required',
        ];
    }

    private function allowedDays($days)
    {
        return Carbon::now()->addDays($days)->format('m/d/Y');
    }
}
