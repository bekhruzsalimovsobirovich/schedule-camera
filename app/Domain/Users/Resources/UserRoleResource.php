<?php

namespace App\Domain\Users\Resources;

use App\Domain\Cameras\Resources\CameraResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRoleResource extends JsonResource
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
            'department' => $this->profile->department->name,
            'name' => $this->name,
            'staff_position' => $this->profile->staffPosition->name,
            'employee_type' => $this->profile->employeeType->name,
            'cameras' => CameraResource::collection($this->cameras)
        ];
    }
}
