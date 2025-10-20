<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'address'            => $this->address,
            'location'           => [
                'latitude'  => $this->location_x,
                'longitude' => $this->location_y,
            ],
            'parent_station_id'  => $this->parent_station_id,
            'opening_time'       => $this->opening_time,
            'closing_time'       => $this->closing_time,
            'branches'           => StationResource::collection($this->whenLoaded('branches')),
            'created_at'         => $this->created_at->toDateTimeString(),
            'updated_at'         => $this->updated_at->toDateTimeString(),
        ];
    }
}
