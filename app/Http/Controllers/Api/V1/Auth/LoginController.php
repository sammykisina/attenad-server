<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Requests\Auth\LoginRequest;
use Domains\Shared\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use JustSteveKing\StatusCode\Http;

class LoginController {
    public function __invoke(LoginRequest $request): JsonResponse {
        $user = User::query()->where('email', $request->get('email'))->first();

        if (!$user || ! Hash::check(value: $request->get(key: 'password'), hashedValue: $user->password)) {
            return response()->json(
                data: [
                    'error' => 1,
                    "message" => "Invalid credentials.Please enter the correct email and Password."
                ],
                status: Http::NOT_FOUND()
            );
        }

        if (config(key: 'hydra.delete_previous_access_tokens_on_login')) {
            $user->tokens()->delete();
        }

        $role = $user->role()->pluck('slug')->all();
        $plain_text_token = $user->createToken('checker-api-token', $role)->plainTextToken;

        return response()->json(
            data: [
                'error' => 0,
                'message' => 'Welcome back to Checker.',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $role[0],
                ],
                'token' => $plain_text_token
            ]
        );
    }
}
