<?php

namespace App\Http\Requests;

use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User */
        $user = auth()->user();
        return $user->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // validate if there's an active donation
        $donations = Donation::where('donation_status_id', Donation::PLANNED)->orWhere('donation_status_id', Donation::ASSIGNED)->orWhere('donation_status_id', Donation::INCOMPLETED)->get();

        foreach ($donations as $donation) {
            $dbDonationDate = Carbon::parse($donation->donation_date);
            // rescue time is 4 hour
            $dbEndDonationDate = Carbon::parse($donation->donation_date)->addHours(4);
            $reqDonationDate = Carbon::parse($this->donation_date);

            $conflictDonation = $reqDonationDate->between($dbDonationDate, $dbEndDonationDate);

            if ($conflictDonation) {
                dd($conflictDonation, "Conflicting with $donation->title, start: $dbDonationDate, finish: $dbEndDonationDate");
            }
        }


        return [
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:3|max:255',
            'donation_date' => 'required|after_or_equal:' . $this->today(),
            'recipient_id' => 'required'
        ];
    }

    private function today()
    {
        return Carbon::today()->toDateTimeString();
    }
}
