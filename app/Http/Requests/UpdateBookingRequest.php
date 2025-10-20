<?php

namespace App\Http\Requests;

use App\Enums\BookingStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Enum;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', new Enum(BookingStatusEnum::class)],
        ];
    }
}
