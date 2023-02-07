<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Lecturer;

use App\Http\Requests\Lecturer\AttendanceStoreRequest;
use App\Http\Resources\Admin\IntakeResource;
use App\Http\Resources\Lecturer\AttendanceResource;
use Domains\Lecturer\Models\Attendance;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LecturerController {
    public function getIntakes(User $lecturer): JsonResponse {
        $intakes = $lecturer->intakes()->with(['courses.modules'])->get();

        return response()->json(
            data: IntakeResource::collection(
                resource: $intakes
            ),
            status: Http::OK()
        );
    }

    public function getAttendances(User $lecturer): JsonResponse {
        $attendance = Attendance::query()
            ->where('user_id', $lecturer->id)
            ->with(relations: ['owner', 'students', 'intake', 'module', 'course'])
            ->get();

        return response()->json(
            data: AttendanceResource::collection(
                resource: $attendance
            ),
            status: Http::OK()
        );
    }

    public function createAttendance(AttendanceStoreRequest $request, User $lecturer): JsonResponse {
        try {
            Attendance::create([
                'name' => $request->get(key: 'name'),
                'week' => $request->get(key: 'week'),
                'content_delivery_type' => $request->get(key: 'contentDeliveryType'),
                'tutorial_group' => $request->get(key:'tutorialGroup') ?? null,
                'user_id' => $lecturer->id,
                'intake_id' =>  $request->get(key: 'intakeId'),
                'course_id' =>  $request->get(key: 'courseId'),
                'module_id' =>  $request->get(key: 'moduleId'),
            ]);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Attendance created successfully.'
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

    // should no be here
    public function getStudentCards(): JsonResponse {
        $studentCards = QueryBuilder::for(subject: User::query()->select('physical_card_id'))
            ->allowedFields('physical_card_id')
            ->defaultSort('-created_at')
            ->allowedFilters(filters: AllowedFilter::exact('role.slug'))
            ->get();

        return response()->json(
            data:$studentCards,
            status: Http::OK()
        );
    }

    public function registerScannedStudent(Request $request, Attendance $attendance): JsonResponse {
        try {
            $student = User::query()->where('physical_card_id', $request->physicalCardId)->first();
            $attendance->students()->syncWithoutDetaching([$student->id]);

            $attendance = Attendance::query()
                 ->where('id', $attendance->id)
                 ->with(relations:  ['owner', 'students', 'intake', 'module', 'course'])
                 ->first();

            return response()->json(
                data: [
                    'error' => 0,
                    'attendance' => new AttendanceResource(
                        resource: $attendance
                    ),
                    'message' => 'Student added to register successfully.'
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

    public function deleteAttendance(Attendance $attendance): JsonResponse {
         try {
            $attendance->delete();

            return response()->json(
                data: [
                    'error' => 0,
                    'attendance' => new AttendanceResource(
                        resource: $attendance
                    ),
                    'message' => 'Attendance deleted successfully.'
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
}
