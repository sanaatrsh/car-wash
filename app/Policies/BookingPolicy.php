<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any bookings (admin only).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view a specific booking.
     * Users can view only their own bookings; admins can view all.
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->hasRole('admin') || $booking->user_id === $user->id;
    }

    /**
     * Determine whether the user can create a booking.
     * Any authenticated user can create one.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['user', 'admin']);
    }

    /**
     * Determine whether the user can update a booking.
     * Admins can update all; users only their own if status allows.
     */
    public function update(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // For normal users: can only update their own pending booking
        return $booking->user_id === $user->id && $booking->status === 'pending';
    }


    /**
     * Determine whether the user can delete a booking (admin only).
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $user->hasRole('admin');
    }
}
