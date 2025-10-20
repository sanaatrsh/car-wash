<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'parent_station_id' => ['nullable', 'exists:stations,id'],
            'name'              => ['sometimes', 'array'],
            'name.en'           => ['sometimes', 'string', 'max:255'],
            'name.ar'           => ['sometimes', 'string', 'max:255'],
            'address'           => ['sometimes', 'array'],
            'address.en'        => ['sometimes', 'string', 'max:255'],
            'address.ar'        => ['sometimes', 'string', 'max:255'],
            'location_x'        => ['sometimes', 'numeric', 'between:-90,90'],
            'location_y'        => ['sometimes', 'numeric', 'between:-180,180'],
            'opening_time'      => ['sometimes', 'date_format:H:i'],
            'closing_time'      => ['sometimes', 'date_format:H:i', 'after:opening_time'],
            'status'            => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'closing_time.after' => 'Closing time must be after opening time.',
            'parent_station_id.exists' => 'Selected parent station does not exist.',
        ];
    }
}
