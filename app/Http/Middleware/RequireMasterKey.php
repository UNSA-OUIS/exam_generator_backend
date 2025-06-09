<?php

namespace App\Http\Middleware;

use App\Helpers\MasterKeyHelper;
use App\Models\MasterKey;
use Closure;
use Illuminate\Http\Request;
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

        if (!isset($decoded['key1'], $decoded['key2'])) {
            return response()->json(['message' => 'Invalid master key format'], 401);
        }

        $hash1 = hash('sha256', $decoded['key1']);
        $hash2 = hash('sha256', $decoded['key2']);
        $interleaved = MasterKeyHelper::interleave($hash1, $hash2);
        $finalHash = hash('sha256', $interleaved);

        $master = MasterKey::first(); // or `where('active', true)->first()` if needed

        if (!$master || !hash_equals($master->hashed_key, $finalHash)) {
            return response()->json(['message' => 'Unauthorized: invalid master key'], 403);
        }

        return $next($request);
    }
}
