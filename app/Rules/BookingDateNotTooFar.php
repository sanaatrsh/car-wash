<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BookingDateNotTooFar implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $bookingDate = Carbon::parse($value);

        if ($bookingDate->greaterThan(Carbon::now()->addMonth())) {
            $fail(__('validation.booking.date_too_far'));
        }
    }
}
