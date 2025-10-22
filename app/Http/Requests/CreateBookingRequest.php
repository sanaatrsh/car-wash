<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\BookingDateNotTooFar;
use App\Rules\BookingWithinWorkingHours;
use App\Rules\NoBookingConflict;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stationId' => ['required', 'exists:stations,id'],
            'washTypeId' => ['required', 'exists:wash_types,id'],
            'date' => [
                'required',
                'date',
                'after_or_equal:today',
                new BookingDateNotTooFar(),
            ],
            'startTime' => [
                'required',
                'date_format:H:i',
                new BookingWithinWorkingHours(
                    $this->input('stationId'),
                    $this->input('date')
                ),
                new NoBookingConflict(
                    $this->input('stationId'),
                    $this->input('date')
                ),
            ],
        ];
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);

        return [
            'stationId' => $validated['stationId'],
            'washTypeId' => $validated['washTypeId'],
            'date' => $validated['date'],
            'startTime' => $validated['startTime'],
        ];
    }
}
