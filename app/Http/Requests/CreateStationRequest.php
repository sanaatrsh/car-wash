<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateStationRequest extends FormRequest
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
            'name'              => ['required', 'string', 'max:255'],
            'address'           => ['required', 'string', 'max:255'],
            'location_x'        => ['required', 'numeric', 'between:-90,90'],
            'location_y'        => ['required', 'numeric', 'between:-180,180'],
            'opening_time'      => ['required', 'date_format:H:i'],
            'closing_time'      => ['required', 'date_format:H:i', 'after:opening_time'],
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
