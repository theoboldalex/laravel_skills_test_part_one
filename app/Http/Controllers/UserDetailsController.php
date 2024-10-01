<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserDetailsController extends Controller
{
    public function __invoke(User $user)
    {
        $userDetails = User::where('id', $user->id)->first();
        return response()->json($userDetails);
    }
}
