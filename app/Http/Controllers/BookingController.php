<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelBookingRequest;
use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\WashType;
use App\Queries\BookingQueryBuilder;
use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    use ApiResponseTrait;

    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index(Request $request)
    {
        $bookings = (new BookingQueryBuilder())
            ->filterByStation($request->station_id)
            ->filterByUser($request->user_id)
            ->filterByStatus($request->status)
            ->filterByDateRange($request->from_date, $request->to_date)
            ->withRelations()
            ->paginate(10);

        return $this->successResponse(
            BookingResource::collection($bookings),
            'Bookings retrieved successfully.'
        );
    }

    public function userIndex()
    {
        $bookings = (new BookingQueryBuilder())
            ->filterByUser(Auth::id())
            ->withRelations()
            ->paginate(10);

        return $this->successResponse(
            BookingResource::collection($bookings),
            'Bookings retrieved successfully.'
        );
    }

    public function store(CreateBookingRequest $request)
    {
        $washType = WashType::findOrFail($request->wash_type_id);

        $booking = Booking::create([
            ...$request->validated(),
            'user_id' => Auth::id(),
            'status' => 'pending',
            'end_time' => $this->bookingService->calculateEndTime($request->start_time, $washType->duration),
        ]);

        $booking->load(['station', 'washType']);

        return $this->successResponse(
            new BookingResource($booking),
            'Booking created successfully.'
        );
    }

    public function cancel(CancelBookingRequest $request, Booking $booking)
    {
        $booking->update([
            'status' => $request->status ?? 'cancelled',
        ]);

        return $this->successResponse(
            new BookingResource($booking),
            'Booking updated successfully.'
        );
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $booking->update([
            'status' => $request->status,
        ]);

        return $this->successResponse(
            new BookingResource($booking),
            'Booking updated successfully.'
        );
    }
}
