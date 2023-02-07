<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Requests\Admin\IntakeStoreRequest;
use App\Http\Resources\Admin\IntakeResource;
use Domains\Admin\Concerns\IntakeStatusEnum;
use Domains\Admin\Models\Intake;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class IntakeController {
    public function index(): JsonResponse {
        $intakes = QueryBuilder::for(subject: Intake::class)
            ->allowedIncludes(['courses.modules'])
            ->defaultSort('-created_at')
            ->get();

        return response()->json(
            data: IntakeResource::collection(
                resource: $intakes
            ),
            status: Http::OK()
        );
    }

    public function store(IntakeStoreRequest $request): JsonResponse {
        try {
            Intake::create([
                'name' => $request->get(key: 'name'),
                'code' => $request->get(key: 'code'),
                'status' => IntakeStatusEnum::ACTIVE->value,
                'created_by' => 'admin'
            ]);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Intake created successfully.'
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

    public function update(Request $request, Intake $intake): JsonResponse {
        try {
            $validated = $request->validate([
                'name'=> [
                    'string'
                ],
                'code' => [
                    Rule::unique('intakes')->ignore($intake->id),
                ],
            ]);

            $updateData = array_merge($validated, ['modified_by' => 'admin']);
            $intake->update($updateData);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Intake updated successfully.'
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

    public function destroy(Intake $intake): JsonResponse {
        try {
            $intake->delete();

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Intake deleted successfully.'
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
