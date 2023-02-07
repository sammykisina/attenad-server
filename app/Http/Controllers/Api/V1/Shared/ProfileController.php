<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Shared;

use App\Http\Resources\Shared\AdminResource;
use App\Http\Resources\Shared\LecturerResource;
use App\Http\Resources\Shared\StudentResource;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use JustSteveKing\StatusCode\Http;

class ProfileController {
    public function getLecturerProfile(User $lecturer): JsonResponse {
        try {
            $lecturer = User::query()
               ->where('id', $lecturer->id)
               ->with(relations: ['role', 'intakes', 'courses', 'modules'])
               ->first();

            return response()->json(
                data: new LecturerResource(resource: $lecturer),
                status: Http::OK()
            );
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Something went wrong.'
                ],
                status: Http::NOT_FOUND()
            );
        }
    }

      public function getAdminProfile(User $admin): JsonResponse {
          try {
              $admin = User::query()
                 ->where('id', $admin->id)
                 ->with(relations: ['role'])
                 ->first();

              return response()->json(
                  data: new AdminResource(resource: $admin),
                  status: Http::OK()
              );
          } catch (\Throwable $th) {
              Log::info($th);
              return response()->json(
                  data: [
                      'error' => 1,
                      'message' => 'Something went wrong.'
                  ],
                  status: Http::NOT_FOUND()
              );
          }
      }

      public function getStudentProfile(User $student): JsonResponse {
          try {
              $student = User::query()
                 ->where('id', $student->id)
                 ->with(relations: ['role', 'course', 'intake'])
                 ->first();

              return response()->json(
                  data: new StudentResource(resource: $student),
                  status: Http::OK()
              );
          } catch (\Throwable $th) {
              Log::info($th);
              return response()->json(
                  data: [
                      'error' => 1,
                      'message' => 'Something went wrong.'
                  ],
                  status: Http::NOT_FOUND()
              );
          }
      }

    public function updatePassword(Request $request, User $user): JsonResponse {
        try {
            $validated = $request->validate([
                'password'=> [
                    'string',
                    'required'
                ]
            ]);

            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Password updated successfully.'
                ],
                status: Http::ACCEPTED()
            );
        } catch (\Throwable $th) {
            return response()->json(
                data: [
                    'error' => 1,
                    'message' => 'Something went wrong.'
                ],
                status: Http::NOT_MODIFIED()
            );
        }
    }

    public function uploadProfilePicture(Request $request, User $user): JsonResponse {
        try {
            $validated = $request->validate([
                'profilePic'=> [
                    'required',
                    'image'
                ]
            ]);

            Log::info("Here");

            $fileName = $user->uuid.'.'.$validated['profilePic']->extension();
            if (is_file(public_path().'profilePics/'.$user->profile_picture_url)) {
                Log::info("image found");
                $file = public_path().'profilePics/'.$user->profile_picture_url;
                unlink($file);
            }



            $validated['profilePic']->move(public_path('profilePics'), $fileName);

            $user->update([
                'profile_picture_url' => $fileName
            ]);

            return response()->json(
                data: [
                    'error' => 0,
                    'message' => 'Profile picture updated successfully.'
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
