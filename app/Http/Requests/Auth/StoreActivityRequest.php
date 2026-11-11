<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sessions' => 'required|array|min:1',
            'sessions.*.day_of_week' => 'required|string',
            'sessions.*.time_slot' => 'required|string',
            'sessions.*.supervisor_id' => 'nullable|exists:users,id',
        ];
    }
}