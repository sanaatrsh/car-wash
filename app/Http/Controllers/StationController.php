<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStationRequest;
use App\Http\Requests\UpdateStationRequest;
use App\Http\Resources\StationResource;
use App\Models\Station;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class StationController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        $stationsQuery = Station::with('branches')->latest();

        //return stations by the nearest
        if ($lat && $lng) {
            $stationsQuery = $stationsQuery->nearLocation($lat, $lng);
        }

        $stations = $stationsQuery->paginate(10);

        return $this->successResponse(
            StationResource::collection($stations),
            'Stations retrieved successfully.'
        );
    }


    public function store(CreateStationRequest $request)
    {
        $station = Station::create($request->validated());

        return $this->successResponse(
            new StationResource($station),
            'Station created successfully.',
            201
        );
    }

    public function update(UpdateStationRequest $request, Station $station)
    {
        $station->update($request->validated());

        return $this->successResponse(
            new StationResource($station),
            'Station updated successfully.'
        );
    }

    public function destroy(Station $station)
    {
        $station->delete();

        return $this->successResponse(null, 'Station deleted successfully.');
    }
}
