<?php

namespace App\Http\Requests;

use App\Enums\BookingStatusEnum;
use App\Models\Station;
use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'stationId' => ['nullable' , 'integer' , Rule::exists('stations', 'id')],
            'status' => ['nullable' , 'string' , Rule::in(array_values(BookingStatusEnum::cases()))],
            'userId' => ['nullable' , 'integer' , Rule::exists('users', 'id')],
            'from'   => ['nullable' , 'date'],
            'to'    => ['nullable' , 'date' , 'after_or_equal:from'],
        ];
    }
}
