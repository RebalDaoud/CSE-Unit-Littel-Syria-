<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVolunteerHoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hours' => ['required', 'numeric', 'min:0', 'max:6'],
            'action' => ['required', 'in:add,set'],
        ];
    }
}