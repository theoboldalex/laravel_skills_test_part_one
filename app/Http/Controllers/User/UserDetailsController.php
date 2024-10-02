<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserDetailsController extends Controller
{
    public function __invoke(User $user)
    {
        return response()->json($user);
    }
}
