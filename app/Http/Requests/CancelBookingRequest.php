<?php

namespace App\Http\Requests;

use App\Enums\BookingStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class CancelBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:' . BookingStatusEnum::CANCELLED->value],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $booking = $this->route('booking');
            if (!$booking) return;

            if ($booking->status->value !== BookingStatusEnum::PENDING->value) {
                $validator->errors()->add('status', __('validation.booking.only_pending_cancel'));
            }

            $startTime = Carbon::parse($booking->date . ' ' . $booking->start_time);
            if (now()->diffInHours($startTime) < 2) {
                $validator->errors()->add('status', __('validation.booking.cancel_two_hours_rule'));
            }
        });
    }
}
