<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Requests\Admin\LecturerStoreRequest;
use App\Http\Resources\Shared\LecturerResource;
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

class LecturerManagementController {
    public function index(): JsonResponse {
        $lecturers = QueryBuilder::for(subject: User::class)
            ->allowedIncludes(includes: ['role', 'courses', 'intakes', 'modules'])
            ->defaultSort('-created_at')
            ->allowedFilters(filters: AllowedFilter::exact('role.slug'))
            ->get();

        return response()->json(
            data: LecturerResource::collection(
                resource: $lecturers
            ),
            status: Http::OK()
        );
    }

    public function store(LecturerStoreRequest $request): JsonResponse {
        try {
            $lecturerRole = Role::query()->where('slug', $request->get(key: 'role'))
                ->first();
            $lecturer = User::create([
                'email' => $request->get(key: 'email'),
                'password' => Hash::make(value: $request->get(key: 'password')),
                'role_id' => $lecturerRole->id,
                'status' => UserStatusEnum::ACTIVE->value,
                'created_by' => UserManagersEnum::ADMIN->value,
            ]);

            $lecturer->intakes()->sync($request->get(key: 'intakes'));
            $lecturer->courses()->sync($request->get(key: 'courses'));
            $lecturer->modules()->sync($request->get(key: 'modules'));

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Lecturer created successfully.'
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

    public function update(Request $request, User $lecturer): JsonResponse {
        try {
            $validated = $request->validate([
                'name'=> [
                    'string'
                ],
                'email' => [
                    'email',
                    Rule::unique('users')->ignore($lecturer->id),
                ],
                'password' => [
                    'string'
                ],
                'role' => [
                    'string',
                ],
                'courses' => [
                    'array'
                ],
                'intakes' => [
                    'array'
                ],
                'modules' => [
                    'array'
                ]
            ]);


            $updateData = array_merge($validated, [
                'modified_by' => UserManagersEnum::ADMIN->value,
                "password" => Hash::make($validated['password'])
            ]);

            $lecturer->update($updateData);

            $lecturer->intakes()->sync($validated['intakes']);
            $lecturer->courses()->sync($validated['courses']);
            $lecturer->modules()->sync($validated['modules']);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Lecturer updated successfully.'
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

    public function destroy(User $lecturer) {
        try {
            $lecturer->intakes()->detach();
            $lecturer->courses()->detach();
            $lecturer->modules()->detach();

            $lecturer->delete();

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Lecturer deleted successfully.'
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
