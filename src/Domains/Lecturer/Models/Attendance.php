<?php

declare(strict_types=1);

namespace Domains\Lecturer\Models;

use Domains\Admin\Models\Course;
use Domains\Admin\Models\Intake;
use Domains\Admin\Models\Module;
use Domains\Shared\Concerns\HasUuid;
use Domains\Shared\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attendance extends Model {
    use HasUuid;
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'week',
        'user_id',
        'content_delivery_type',
        'tutorial_group',
        'intake_id',
        'course_id',
        'module_id',
    ];

    public function intake(): BelongsTo {
        return $this->belongsTo(
            related: Intake::class,
            foreignKey: 'intake_id'
        );
    }

    public function module(): BelongsTo {
        return $this->belongsTo(
            related: Module::class,
            foreignKey: 'module_id'
        );
    }

    public function course(): BelongsTo {
        return $this->belongsTo(
            related: Course::class,
            foreignKey: 'course_id'
        );
    }

    public function owner(): BelongsTo {
        return $this->belongsTo(
            related: User::class,
            foreignKey: 'user_id'
        );
    }

    public function students(): BelongsToMany {
        return $this->belongsToMany(
            related: User::class,
            table: 'attendance_user'
        );
    }
}
