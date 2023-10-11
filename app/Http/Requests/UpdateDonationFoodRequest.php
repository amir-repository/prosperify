<?php

namespace App\Http\Requests;

use App\Models\Donation;
use App\Models\DonationFood;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDonationFoodRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $maxAmount = null;
        $donationFood = DonationFood::where(['donation_id' => $this->donation->id, 'food_id' => $this->food->id])->first();

        if (in_array($donationFood->food_donation_status_id, [1, 2, 3, 4])) {
            $maxAmount = $this->donation_food_max_amount;
        } else {
            $maxAmount = $this->donation_food_original_amount;
        }

        return [
            'amount' => 'required|gt:0|lt:' . (int)$maxAmount,
            'food_id' => 'required',
            'note' => 'required|min:3'
        ];
    }
}
