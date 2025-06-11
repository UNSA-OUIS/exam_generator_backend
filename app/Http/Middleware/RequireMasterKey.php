<?php

namespace App\Http\Middleware;

use App\Helpers\MasterKeyHelper;
use App\Models\MasterKey;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class RequireMasterKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Missing master key'], 401);
        }

        $decoded = json_decode(base64_decode($token), true);

        if (!isset($decoded['email1'], $decoded['key1'], $decoded['email2'], $decoded['key2'])) {
            return response()->json(['message' => 'Invalid master key format'], 401);
        }

        $user1 = User::where('email', $decoded['email1'])->firstOrFail();
        $user2 = User::where('email', $decoded['email2'])->firstOrFail();

        if (
            $user1 === !$user2 ||
            !Hash::check($decoded['key1'], $user1->password) ||
            !Hash::check($decoded['key2'], $user2->password)
        ) {
            return response()->json(['message' => 'Unauthorized: invalid credentials'], 403);
        }

        // Optionally restrict which users can be used (e.g., only "Admin" roles)
        /*if (!$user1->is_admin || !$user2->is_admin) {
            return response()->json(['message' => 'Unauthorized: not admin users'], 403);
        }*/

        // Passed two-user auth
        return $next($request);
    }
}
