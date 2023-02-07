<?php

declare(strict_types=1);

namespace Domains\Shared\Models;

use Database\Factories\UserFactory;
use Domains\Admin\Models\Course;
use Domains\Admin\Models\Intake;
use Domains\Admin\Models\Module;
use Domains\Lecturer\Models\Attendance;
use Domains\Shared\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasUuid;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'uuid',
        "physical_card_id",
        'email',
        'password',
        'role_id',
        "course_id",
        'intake_id',
        'status',
        'created_by',
        'modified_by',
        'profile_picture_url'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verified_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo {
        return $this->belongsTo(
            related: Role::class,
            foreignKey: 'role_id'
        );
    }

    public function course(): BelongsTo {
        return $this->belongsTo(
            related: Course::class,
            foreignKey: 'course_id'
        );
    }

    public function intake(): BelongsTo {
        return $this->belongsTo(
            related: Intake::class,
            foreignKey: 'intake_id'
        );
    }

    public function intakes(): BelongsToMany {
        return $this->belongsToMany(
            related: Intake::class,
            table: 'intake_user'
        );
    }

    public function courses(): BelongsToMany {
        return $this->belongsToMany(
            related: Course::class,
            table: 'course_user'
        );
    }

    public function modules(): BelongsToMany {
        return $this->belongsToMany(
            related: Module::class,
            table: 'module_user'
        );
    }

    public function attendances(): BelongsToMany {
        return $this->belongsToMany(
            related: Attendance::class,
            table: 'attendance_user'
        );
    }

    protected static function newFactory(): UserFactory {
        return new UserFactory;
    }
}
