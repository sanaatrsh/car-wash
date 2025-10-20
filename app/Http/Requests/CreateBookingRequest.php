<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Booking;
use App\Models\Station;
use Carbon\Carbon;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'station_id'   => ['required', 'exists:stations,id'],
            'wash_type_id' => ['required', 'exists:wash_types,id'],
            'date'         => ['required', 'date', 'after_or_equal:today'],
            'start_time'   => ['required', 'date_format:H:i'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateBookingLogic($validator);
        });
    }

    protected function validateBookingLogic($validator)
    {
        $station = Station::find($this->station_id);
        if (!$station) return;

        $washDuration = 45;
        $startTime = Carbon::parse($this->start_time);
        $endTime = (clone $startTime)->addMinutes($washDuration);

        $bookingDate = Carbon::parse($this->date);

        if ($bookingDate->isPast()) {
            $validator->errors()->add('date', __('validation.booking.date_in_past'));
        }

        if ($bookingDate->greaterThan(Carbon::now()->addMonth())) {
            $validator->errors()->add('date', __('validation.booking.date_too_far'));
        }

        if ($station->opening_time && $station->closing_time) {
            $workStart = Carbon::parse($station->opening_time);
            $workEnd = Carbon::parse($station->closing_time);

            if ($startTime->lt($workStart) || $endTime->gt($workEnd)) {
                $validator->errors()->add('start_time', __('validation.booking.outside_working_hours'));
            }
        }

        $conflict = Booking::where('station_id', $this->station_id)
            ->where('date', $bookingDate->toDateString())
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<', $startTime)
                            ->where('end_time', '>', $endTime);
                    });
            })
            ->exists();

        if ($conflict) {
            $validator->errors()->add('start_time', __('validation.booking.conflict_time_slot'));
        }
    }
}
