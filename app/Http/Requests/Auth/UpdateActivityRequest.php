<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'sessions' => 'sometimes|array|min:1',
            'sessions.*.day_of_week' => 'required_with:sessions|string',
            'sessions.*.time_slot' => 'required_with:sessions|string',
            'sessions.*.supervisor_id' => 'nullable|exists:users,id',
        ];
    }
}