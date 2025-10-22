<?php

namespace App\Queries;

use App\Http\Requests\FilterBookingRequest;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingQueryBuilder
{
    /** @var Builder<Booking> */
    protected Builder $query;

    public function __construct()
    {
        $this->query = Booking::query();
    }

    public static function make(): self
    {
        return new self();
    }

    public function applyFilters(FilterBookingRequest $request): self
    {
        $this->filterByStation($request->input('stationId'))
            ->filterByStatus($request->input('status'))
            ->filterByUser($request->input('userId'))
            ->filterByDateRange($request->input('from'), $request->input('to'));

        return $this;
    }

    public function filterByStation(?int $stationId): self
    {
        if ($stationId && Auth::user()->hasRole('admin'))
            $this->query->where('station_id', $stationId);

        return $this;
    }

    public function filterByUser(?int $userId ): self
    {
        $userId = Auth::user()->hasRole('admin') ? $userId : Auth::user()->id;

        if ($userId)
            $this->query->where('user_id', $userId);

        return $this;
    }

    public function filterByStatus(?string $status): self
    {
        if ($status)
            $this->query->where('status', $status);

        return $this;
    }

    public function filterByDateRange(?string $from, ?string $to): self
    {
        if ($from)
            $this->query->whereDate('date', '>=', $from);

        if ($to)
            $this->query->whereDate('date', '<=', $to);

        return $this;
    }

    public function withRelations(array $relations = ['user', 'station', 'washType']): self
    {
        $this->query->with($relations);

        return $this;
    }

    public function getQuery(): Builder
    {
        return $this->query;
    }
}
