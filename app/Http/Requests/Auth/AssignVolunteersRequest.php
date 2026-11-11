<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AssignVolunteersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'volunteer_ids' => 'required|array',
            'volunteer_ids.*' => 'exists:users,id', 
        ];
    }
}