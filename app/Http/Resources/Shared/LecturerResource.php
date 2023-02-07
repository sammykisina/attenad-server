<?php

declare(strict_types=1);

namespace App\Http\Resources\Shared;

use App\Http\Resources\Admin\CourseResource;
use App\Http\Resources\Admin\IntakeResource;
use App\Http\Resources\Admin\ModuleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LecturerResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'lecturer',
            'attributes' => [
                'uuid' => $this->uuid,
                'email' => $this->email,
                'status' => $this->status,
                'createdBy' => $this->created_by,
                'modifiedBy' => $this->modified_by,
                'profilePictureUrl' => $this->profile_picture_url ? url('profilePics/'.$this->profile_picture_url) : null
            ],
            'relationships' => [
                'role' => new RoleResource(
                    resource: $this->whenLoaded(relationship: 'role')
                ),
                'courses' => CourseResource::collection(
                    resource : $this->whenLoaded(relationship: 'courses')
                ),
                'intakes' => IntakeResource::collection(
                    resource: $this->whenLoaded(relationship: 'intakes')
                ),
                'modules' => ModuleResource::collection(
                    resource: $this->whenLoaded(relationship: 'modules')
                )
            ]
        ];
    }
}
