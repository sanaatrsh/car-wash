<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelBookingRequest;
use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\FilterBookingRequest;
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


    public function index(FilterBookingRequest $request , BookingQueryBuilder $bookingQueryBuilder)
    {
        $bookings = $bookingQueryBuilder
            ->applyFilters($request)
            ->withRelations(['station', 'washType'])
            ->getQuery();

        return BookingResource::collection(
            $bookings->paginate($request->get('perPage' , 10))
        );
    }

    public function store(CreateBookingRequest $request , BookingService $bookingService)
    {
        $duration = WashType::query()
            ->where('id', $request->input('washTypeId'))
            ->value('duration');

        $booking = Booking::query()->create([
            ...$request->validated(),
            'end_time' => $bookingService->calculateEndTime($request->input('startTime'), $duration),
        ]);

        return BookingResource::make($booking->load(['station', 'washType']));
    }


    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $booking->update(['status' => $request->status]);

       return response()->noContent();
    }
}
