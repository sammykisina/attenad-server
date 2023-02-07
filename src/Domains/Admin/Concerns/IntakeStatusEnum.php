<?php

declare(strict_types=1);

namespace Domains\Admin\Concerns;

enum IntakeStatusEnum: string {
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
