<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStationRequest;
use App\Http\Requests\UpdateStationRequest;
use App\Http\Resources\StationResource;
use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $stations = Station::with('branches')->paginate(10);

        return response()->json([
            'stations' => StationResource::collection($stations),
        ], 200);
    }

    public function show(Station $station)
    {
        $station = Station::with('branches');

        return response()->json([
            'station' => new StationResource($station)
        ], 200);
    }

    public function store(CreateStationRequest $request)
    {
        $data = $request->validated();

        $station = Station::create($data);

        return response()->json([
            'station' => new StationResource($station),
            'message' => 'Station created successfully.'
        ], 201);
    }

    public function update(UpdateStationRequest $request, Station $station)
    {
        $data = $request->validated();

        $station->update($data);

        return response()->json([
            'station' => new StationResource($station),
            'message' => 'Station updated successfully.'
        ], 200);
    }

    public function destroy(Station $station)
    {
        $station->delete();

        return response()->json([
            'message' => 'Station deleted successfully.'
        ], 200);
    }
}
