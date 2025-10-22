<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWashTypeRequest;
use App\Http\Requests\UpdateWashTypeRequest;
use App\Http\Resources\WashTypeResource;
use App\Models\WashType;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class WashTypeController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $washTypes = WashType::all();

        return $this->successResponse(
            WashTypeResource::collection($washTypes),
            'Wash types retrieved successfully.'
        );
    }

    public function store(CreateWashTypeRequest $request)
    {
        $washType = WashType::create($request->validated());

        return $this->successResponse(
            new WashTypeResource($washType),
            'Wash type created successfully.',
            201
        );
    }

    public function update(UpdateWashTypeRequest $request, WashType $washType)
    {
        $washType->update($request->validated());

        return $this->successResponse(
            new WashTypeResource($washType),
            'Wash type updated successfully.'
        );
    }

    public function destroy(WashType $washType)
    {
        $washType->delete();

        return $this->successResponse(null, 'Wash type deleted successfully.');
    }
}
