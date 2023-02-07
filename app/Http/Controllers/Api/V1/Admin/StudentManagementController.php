<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Requests\Admin\StudentRequest;
use App\Http\Resources\Shared\StudentResource;
use Domains\Shared\Concerns\UserManagersEnum;
use Domains\Shared\Concerns\UserStatusEnum;
use Domains\Shared\Models\Role;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StudentManagementController {
    public function index(): JsonResponse {
        $students = QueryBuilder::for(subject: User::class)
            ->allowedIncludes(includes: ['role', 'course', 'intake'])
            ->defaultSort('-created_at')
            ->allowedFilters(filters: AllowedFilter::exact('role.slug'))
            ->get();

        return response()->json(
            data: StudentResource::collection(
                resource: $students,
            ),
            status: Http::OK()
        );
    }

    public function store(StudentRequest $request): JsonResponse {
        try {
            $studentRole = Role::query()->where('slug', $request->get(key: 'role'))->first();
            User::create([
                'email' => $request->get(key: 'email'),
                'physical_card_id' => $request->get(key: 'physicalCardId'),
                'password' => Hash::make(value: $request->get(key: 'password')),
                'role_id' => $studentRole->id,
                'course_id' => $request->get(key: 'course_id'),
                'intake_id' => $request->get(key: 'intake_id'),
                'status' => UserStatusEnum::ACTIVE->value,
                'created_by' => UserManagersEnum::ADMIN->value,
            ]);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Student created successfully.'
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


    public function update(Request $request, User $student): JsonResponse {
        try {
            $validated = $request->validate([
                'name'=> [
                    'string'
                ],
                'email' => [
                    'required', 'email', Rule::unique('users')->ignore($student->id),
                ],
                'password' => [
                    'string'
                ],
                'role' => [
                    'string',
                ],
                'physicalCardId' => [
                    'string',
                ],
                'course_id' => [
                    'exists:courses,id'
                ],
                'intake_id' => [
                    'exists:intakes,id'
                ]

            ]);

            $updateData = array_merge($validated, [
                'modified_by' => UserManagersEnum::ADMIN->value,
                "password" => Hash::make($validated['password']),
                ''
            ]);
            $student->update($updateData);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Student updated successfully.'
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

    public function destroy(User $student): JsonResponse {
        try {
            $student->delete();

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Student deleted successfully.'
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
