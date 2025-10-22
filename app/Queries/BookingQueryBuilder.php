<?php

namespace App\Queries;

use App\Models\Booking;

class BookingQueryBuilder
{
    protected $query;

    public function __construct()
    {
        $this->query = Booking::query();
    }

    public static function make(): self
    {
        return new self();
    }

    public function filterByStation($stationId)
    {
        if ($stationId) {
            $this->query->where('station_id', $stationId);
        }
        return $this;
    }

    public function filterByUser($userId)
    {
        if ($userId) {
            $this->query->where('user_id', $userId);
        }
        return $this;
    }

    public function filterByStatus($status)
    {
        if ($status) {
            $this->query->where('status', $status);
        }
        return $this;
    }

    public function filterByDateRange($from, $to)
    {
        if ($from) {
            $this->query->whereDate('date', '>=', $from);
        }
        if ($to) {
            $this->query->whereDate('date', '<=', $to);
        }
        return $this;
    }

    public function withRelations()
    {
        $this->query->with(['user', 'station', 'washType']);
        return $this;
    }

    public function paginate($perPage = 10)
    {
        return $this->query->orderBy('date', 'desc')->paginate($perPage);
    }
}
