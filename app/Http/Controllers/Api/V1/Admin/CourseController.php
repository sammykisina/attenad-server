<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Requests\Admin\CourseStoreRequest;
use App\Http\Resources\Admin\CourseResource;
use Domains\Admin\Concerns\CourseStatusEnum;
use Domains\Admin\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class CourseController {
    public function index(): JsonResponse {
        $courses = QueryBuilder::for(subject: Course::class)
            ->allowedIncludes(includes: ['modules'])
            ->defaultSort('-created_at')
            ->get();

        return response()->json(
            data: CourseResource::collection(
                resource: $courses
            ),
            status: Http::OK()
        );
    }

    public function store(CourseStoreRequest $request) {
        try {
            Course::create([
                'name' => $request->get(key: 'name'),
                'code' => $request->get(key: 'code'),
                'status' => CourseStatusEnum::ACTIVE->value,
                'created_by' => 'admin'
            ]);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Course created successfully.'
                ],
                status: Http::CREATED()
            );
        } catch (\Throwable $th) {
            Log::info($th);

            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Something went wrong.'
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }
    }

    public function update(Request $request, Course $course): JsonResponse {
        try {
            $validated = $request->validate([
                'name'=> [
                    'string'
                ],
                'code' => [
                    Rule::unique('courses')->ignore($course->id),
                ],
            ]);

            $updateData = array_merge($validated, ['modified_by' => 'admin']);
            $course->update($updateData);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Course updated successfully.'
                ],
                status: Http::ACCEPTED()
            );
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Something went wrong.'
                ],
                status: Http::NOT_MODIFIED()
            );
        }
    }

    public function destroy(Course $course): JsonResponse {
        try {
            $course->delete();

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Course deleted successfully.'
                ],
                status: Http::ACCEPTED()
            );
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Something went wrong.'
                ],
                status: Http::NOT_IMPLEMENTED()
            );
        }
    }
}
