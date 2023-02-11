<?php

declare(strict_types=1);

namespace App\Http\Resources\Shared;

use App\Http\Resources\Admin\CourseResource;
use App\Http\Resources\Admin\IntakeResource;
use App\Http\Resources\Lecturer\AttendanceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'student',
            'attributes' => [
                'uuid' => $this->uuid,
                'physicalCardId' => $this->physical_card_id,

                'email' => $this->email,
                'status' => $this->status,
                'createdBy' => $this->created_by,
                'createdAt' => $this->created_at,
                'modifiedBy' => $this->modified_by,
                'profilePictureUrl' => $this->profile_picture_url ? url('profilePics/'.$this->profile_picture_url) : null
            ],
            'relationships' => [
                'role' => new RoleResource(
                    resource: $this->whenLoaded(relationship: 'role')
                ),
                'course' => new CourseResource(
                    resource : $this->whenLoaded(relationship: 'course')
                ),
                'intake' => new IntakeResource(
                    resource: $this->whenLoaded(relationship: 'intake')
                ),
                'attendances' => AttendanceResource::collection(
                    resource: $this->whenLoaded(relationship: 'attendances')
                )
            ]
        ];
    }
}
