<?php

declare(strict_types=1);

namespace Domains\Admin\Concerns;

enum CourseStatusEnum: string {
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
