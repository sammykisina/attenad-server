<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Resources\Admin\CourseResource;
use App\Http\Resources\Admin\IntakeResource;
use Domains\Admin\Models\Course;
use Domains\Admin\Models\Intake;
use Domains\Admin\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use JustSteveKing\StatusCode\Http;

class SchoolManagementController {
    public function linkIntakeToCourse(Intake $intake, Course $course) {
        try {
            $intake->courses()->sync([$course->id]);

            $intake = Intake::query()
                ->with(relations: ['courses'])
                ->where('id', $intake->id)
                ->first();

            return response()->json(
                data: [
                    'error' => 0,
                    'intake' => new IntakeResource(resource: $intake),
                    'message' => "Intake linked to the chosen course."
                ],
                status: Http::ACCEPTED()
            );
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => "Something went wrong."
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }
    }

    public function linkCourseToModule(Course $course, Module $module): JsonResponse {
        try {
            $course->modules()->sync([$module->id]);
            $course = Course::query()
                ->with(relations: ['modules'])
                ->where('id', $course->id)
                ->first();

            return response()->json(
                data: [
                    'error' => 0,
                    'course' => new CourseResource(resource: $course),
                    'message' => "Course linked to the chosen module."
                ],
                status: Http::ACCEPTED()
            );
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => "Something went wrong."
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }
    }
}
