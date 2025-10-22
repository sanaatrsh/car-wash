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

        return StationResource::collection(
            $stationsQuery->paginate($request->get('perPage' , 10))
        );
    }


    public function store(CreateStationRequest $request)
    {
        return StationResource::make(
            Station::query()->create($request->validated())
        );
    }

    public function update(UpdateStationRequest $request, Station $station)
    {
        $station->update($request->validated());

        return StationResource::make(
            $station->fresh()
        );
    }

    public function destroy(Station $station)
    {
        $station->delete();

        return response()->noContent();
    }
}
