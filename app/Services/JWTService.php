<?php

namespace App\Services;

use App\Facades\JWTAuth;

class JWTService
{
    public function generateToken($user): string
    {
        return JWTAuth::fromUser($user);
    }

    public function attempt(array $credentials): ?string
    {
        return JWTAuth::attempt($credentials);
    }

    public function user()
    {
        return JWTAuth::user();
    }

    public function me()
    {
        return JWTAuth::parseToken()->authenticate();
    }

    public function invalidate(): void
    {
        JWTAuth::parseToken()->invalidate();
    }

    public function refresh(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }
}
