<?php

namespace App\Http\Requests;

use App\Models\Station;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterBookingRequest extends FormRequest
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
            'stationId' => ['nullable' , 'integer' , Rule::exists('stations', 'id')],
            'status' => ['nullable' , 'string'],
            'userId' => ['nullable' , 'integer' , Rule::exists('users', 'id')],
            'from'   => ['nullable' , 'date'],
            'to'    => ['nullable' , 'date' , 'after_or_equal:from'],
        ];
    }
}
