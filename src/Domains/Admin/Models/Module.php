<?php

declare(strict_types=1);

namespace Domains\Admin\Models;

use Domains\Lecturer\Models\Attendance;
use Domains\Shared\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model {
    use HasUuid;
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'status',
        'created_by',
        'modified_by'
    ];

    public function lecturers(): BelongsToMany {
        return $this->belongsToMany(
            related: User::class,
            table: 'module_user'
        );
    }

    public function courses(): BelongsToMany {
        return $this->belongsToMany(
            related: Course::class,
            table: 'course_module'
        );
    }

    public function attendance(): HasMany {
        return $this->hasMany(
            related: Attendance::class,
            foreignKey: 'module_id'
        );
    }
}
