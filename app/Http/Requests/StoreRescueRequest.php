<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreRescueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
