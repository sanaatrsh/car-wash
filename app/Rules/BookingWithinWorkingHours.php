<?php

namespace App\Rules;

use App\Models\Station;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BookingWithinWorkingHours implements ValidationRule
{
    protected const int WASH_DURATION = 45;

    public function __construct(
        protected ?int $stationId,
        protected ?string $date
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->stationId || !$this->date) {
            return;
        }

        $station = Station::find($this->stationId);

        if (!$station || !$station->opening_time || !$station->closing_time) {
            return;
        }

        $startTime = Carbon::parse($value);
        $endTime = (clone $startTime)->addMinutes(self::WASH_DURATION);

        $workStart = Carbon::parse($station->opening_time);
        $workEnd = Carbon::parse($station->closing_time);

        if ($startTime->lt($workStart) || $endTime->gt($workEnd)) {
            $fail(__('validation.booking.outside_working_hours'));
        }
    }
}
