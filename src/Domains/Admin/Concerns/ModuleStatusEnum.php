<?php

declare(strict_types=1);

namespace Domains\Admin\Concerns;

enum ModuleStatusEnum: string {
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
}
