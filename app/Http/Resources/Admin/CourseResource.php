<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->id,
            'type' => 'course',
            'attributes' => [
                'uuid' => $this->uuid,
                'code' =>$this->code,
                'status' => $this->status,
                'name' => $this->name,
                'createdBy' => $this->created_by,
                'createdAt' => $this->created_at,
                'modifiedBy' => $this->modified_by,
            ],
            'relationships' => [
                'modules' => ModuleResource::collection(resource: $this->whenLoaded(relationship: 'modules'))
            ]
        ];
    }
}
