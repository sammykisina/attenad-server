<?php

declare(strict_types=1);

namespace App\Http\Resources\Lecturer;

use App\Http\Resources\Admin\CourseResource;
use App\Http\Resources\Admin\IntakeResource;
use App\Http\Resources\Admin\ModuleResource;
use App\Http\Resources\Shared\LecturerResource;
use App\Http\Resources\Shared\StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'type' => 'attendance',
            'attributes' => [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'week' => $this->week,
                'contentDeliveryType' => $this->content_delivery_type,
                'tutorialGroup' => $this->tutorial_group,
                'createdAt' => $this->created_at
            ],
            'relationships' => [
                'owner' => new LecturerResource(
                    resource: $this->whenLoaded(
                        relationship: 'owner'
                    )
                ),
                'students' => StudentResource::collection(
                    resource: $this->whenLoaded(
                        relationship: 'students'
                    )
                ),
                'intake' => new IntakeResource(
                    resource: $this->whenLoaded(
                        relationship: 'intake'
                    )
                ),
                'module' => new ModuleResource(
                    resource: $this->whenLoaded(
                        relationship: 'module'
                    )
                ),
                'course' => new CourseResource(
                    resource: $this->whenLoaded(
                        relationship: 'course'
                    )
                )
            ]
        ];
    }
}
