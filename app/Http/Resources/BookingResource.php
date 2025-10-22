<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'station' => new StationResource($this->whenLoaded('station')),
            'washType' => new WashTypeResource($this->whenLoaded('washType')),
            'date' => $this->date,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
            'status' => $this->status->value,
            'createdAt' => $this->created_at->toDateTimeString(),
            'updatedAt' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
