<?php

declare(strict_types=1);

namespace App\Http\Resources\Shared;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'type' => 'role',
            'attributes' => [
                'name' => $this->name,
                'slug' => $this->slug
            ]
        ];
    }
}
