<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var \App\Models\User */
        $user = auth()->user();
        return $user->hasAnyRole(['admin', 'donor']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nik' => 'required',
            'name' => 'required|min:2|max:100',
            'address' => 'required|min:10|max:255',
            'phone' => 'required|min:8|max:16',
            'family_members' => 'required|min:1',
            'photo' => 'required',
        ];
    }
}
