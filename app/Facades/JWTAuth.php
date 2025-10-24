<?php

namespace App\Facades;

use Tymon\JWTAuth\Facades\JWTAuth as BaseJWTAuth;

/**
 * @method static \Tymon\JWTAuth\Contracts\JWTSubject user()
 * @method static string fromUser(\Illuminate\Contracts\Auth\Authenticatable $user)
 * @method static bool attempt(array $credentials)
 * @method static string refresh($token = null)
 * @method static void invalidate($token = null)
 */
class JWTAuth extends BaseJWTAuth {}
