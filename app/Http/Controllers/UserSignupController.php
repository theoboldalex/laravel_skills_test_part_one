<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSignupRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserSignupController extends Controller
{
    public function __construct(private User $user)
    {    
    }

    public function __invoke(UserSignupRequest $request): JsonResponse
    {
        try {
            $user = $this->user->create($request->only(['name', 'password', 'email', 'created', 'role']));
        } catch (\Throwable $th) {
            return $this->handleError($th);
        }

        return response()->json($user, Response::HTTP_CREATED);
    }

    private function handleError(Throwable $th): JsonResponse
    {
        Log::error($th->getMessage());
        return response()->json(
            [
                'success' => false,
                'message' => 'Unable to create user'
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
