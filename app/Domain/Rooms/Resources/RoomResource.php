<?php

namespace App\Domain\Rooms\Resources;

use App\Domain\Cameras\Resources\CameraResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'floor' => $this->floor->name ?? null,
            'cameras' => CameraResource::collection($this->cameras()->where('room_id',$this->id)->where('favourite', true)->get())
        ];
    }
}
