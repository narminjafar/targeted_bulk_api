<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\CustomApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function register(RegisterRequest $request)
    {
        $result = $this->userService->register($request->validated());
        return response()->json($result, 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->userService->login($request->validated());
        
        if (!$result) {
            throw new CustomApiException(
                'Göstərilən etimadnamələr qeydlərimizə uyğun gəlmir.',
                'UNAUTHORIZED', 
                Response::HTTP_UNAUTHORIZED 
            );
        }

        return response()->json($result);
    }

    public function me()
    {
        return response()->json($this->userService->me());
    }

    public function logout()
    {
        $this->userService->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return response()->json($this->userService->refresh());
    }
}
