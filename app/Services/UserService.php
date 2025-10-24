<?php
namespace App\Services;

use App\Exceptions\CustomApiException;
use App\Repositories\UserRepository;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Response;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
        private JWTService $jwtService
    ) {}

    public function register(array $data): array
    {
        if ($this->userRepo->all(['email' => $data['email']])->isNotEmpty()) {
             throw new CustomApiException(
                'Qeydiyyat məlumatlarında səhv var.',
                'VALIDATION_ERROR',
                Response::HTTP_UNPROCESSABLE_ENTITY, // 422
                ['email' => ['Bu e-poçt ünvanı artıq istifadə olunur.']]
            );
        }

        $user = $this->userRepo->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $this->jwtService->generateToken($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(array $credentials): ?array
    {
        if (!$token = $this->jwtService->attempt($credentials)) {
            return null;
        }

        return [
            'user' => $this->jwtService->user(),
            'token' => $token,
        ];
    }

    public function me()
    {
        return $this->jwtService->me();
    }

    public function logout(): void
    {
        $this->jwtService->invalidate();
    }

    public function refresh(): array
    {
        $token = $this->jwtService->refresh();

        return [
            'user' => $this->jwtService->me(),
            'token' => $token,
        ];
    }
}
