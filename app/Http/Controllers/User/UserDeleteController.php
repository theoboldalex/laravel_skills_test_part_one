<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserDeleteController extends Controller
{
    public function __construct(private User $user) {}

    public function __invoke(User $user): JsonResponse
    {
        try {
            if (! $this->isUserAccountWithinCoolingOffPeriod($user)) {
                throw new Exception("The user account with id {$user->id} is older than 14 days and cannot be deleted");
            }

            $this->user->destroy($user->id);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Unable to delete user with id {$user->id}",
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    private function isUserAccountWithinCoolingOffPeriod(User $user): bool
    {
        $thresholdToDeleteUserAccount = (new DateTime)
            ->sub(DateInterval::createFromDateString('14 days'))
            ->format('Y-m-d H:i:s');

        return $user->created > $thresholdToDeleteUserAccount;
    }
}
