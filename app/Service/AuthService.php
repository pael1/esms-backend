<?php

namespace App\Service;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserAccountResource;
use Illuminate\Support\Facades\RateLimiter;
use App\Interface\Service\AuthServiceInterface;
use App\Interface\Repository\UserRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(object $payload)
    {
        $key = $this->throttleKey($payload->username);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            // Log lockout as suspicious
            activity()
                ->event('login_lockout')
                ->withProperties([
                    'username'    => $payload->username,
                    'ip'          => request()->ip(),
                    'user_agent'  => request()->userAgent(),
                    'attempts'    => RateLimiter::attempts($key),
                    'retry_after' => $seconds,
                ])
                ->log('Login locked out due to too many attempts.');

            return response()->json([
                'message'      => 'Too many login attempts. Please try again later.',
                'retry_after'  => $seconds,
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->findByUsername($payload->username);

        RateLimiter::hit($key, 60); // decay after 60 seconds

        if (! $user || ! Hash::check($payload->password, $user->password)) {

            activity()
                ->event('login_failed')
                ->withProperties([
                    'username'   => $payload->username,
                    'ip'         => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'reason'     => $user ? 'invalid_password' : 'invalid_username',
                ])
                ->log('Login attempt failed.');

            return response()->json([
                'message' => 'Invalid credentials.',
            ], Response::HTTP_BAD_REQUEST);
        }

        RateLimiter::clear($key);

        $token = $user->createToken('auth-token')->plainTextToken;

        $data = (object) [
            'token' => $token,
            'user'  => UserResource::make($user),
        ];

        //Log successful login
        activity()
            ->event('login_success')
            ->causedBy($user)
            ->withProperties([
                'user_id'    => $user->id,
                'username'   => $payload->username,
                'ip'         => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('User logged in successfully.');

        return AuthResource::make($data);

    }

    protected function throttleKey(string $username): string
    {
        return Str::lower($username) . '|' . request()->ip();
    }

    public function logout(object $payload)
    {
        $payload->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logout',
        ], Response::HTTP_OK);
    }
}
