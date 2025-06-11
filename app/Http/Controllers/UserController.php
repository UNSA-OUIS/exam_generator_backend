<?php

namespace App\Http\Controllers;

use App\Helpers\MasterKeyHelper;
use App\Models\MasterKey;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function resetPassword(Request $request)
    {
        $credentials = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        $user->password = $credentials['password'];
        $user->save();

        return response()->json([
            'message' => 'Password reset successful',
            'user' => $user,
        ]);
    }
}
