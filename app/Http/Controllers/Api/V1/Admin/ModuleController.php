<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Requests\Admin\ModuleStoreRequest;
use App\Http\Resources\Admin\ModuleResource;
use Domains\Admin\Concerns\ModuleStatusEnum;
use Domains\Admin\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use JustSteveKing\StatusCode\Http;
use Spatie\QueryBuilder\QueryBuilder;

class ModuleController {
    public function index(): JsonResponse {
        $modules = QueryBuilder::for(subject: Module::class)
            ->allowedIncludes(includes: ['courses.intakes'])
            ->defaultSort('-created_at')
            ->get();

        return response()->json(
            data: ModuleResource::collection(
                resource: $modules
            ),
            status: Http::OK()
        );
    }

     public function store(ModuleStoreRequest $request): JsonResponse {
         try {
             Module::create([
                 'name' => $request->get(key: 'name'),
                 'code' => $request->get(key: 'code'),
                 'status' => ModuleStatusEnum::ACTIVE->value,
                 'created_by' => 'admin'
             ]);

             return response()->json(
                 data: [
                     'error' => 0,
                     'message' => 'Module created successfully.'
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

    public function update(Request $request, Module $module): JsonResponse {
        try {
            $validated = $request->validate([
                'name'=> [
                    'string'
                ],
                'code' => [
                    Rule::unique('modules')->ignore($module->id),
                ],
            ]);

            $updateData = array_merge($validated, ['modified_by' => 'admin']);
            $module->update($updateData);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Module updated successfully.'
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

     public function destroy(Module $module): JsonResponse {
         try {
             $module->delete();

             return response()->json(
                 data: [
                     'error' => 0,
                     'message' => 'Module deleted successfully.'
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
