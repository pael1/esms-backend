<?php

namespace App\Service;

use App\Http\Resources\AuthResource;
use App\Http\Resources\UserAccountResource;
use App\Http\Resources\UserResource;
use App\Interface\Repository\UserRepositoryInterface;
use App\Interface\Service\AuthServiceInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(object $payload)
    {
        $user = $this->userRepository->findByUsername($payload->username);

        if (! $user) {
            return response()->json([
                'message' => 'Invalid Username',
            ], 400);
        }

        //since the esms was using old encryption md5()
        // we will convert it to bcrypt in laravel 11
        // $login_password = md5($payload->password); //encrypt the password to md5
        // $user_password = Hash::make(strtolower($user->Password)); //user password encrypted with md5

        // if (! Hash::check($login_password, $user_password)) {
        //     return response()->json([
        //         'message' => 'Invalid password',
        //     ], Response::HTTP_BAD_REQUEST);
        // }
        // $data = (object) [
        //     'token' => $user->createToken('auth-token')->plainTextToken,
        //     // 'user' => new UserResource($user)
        //     'user' => new UserAccountResource($user),
        // ];

        if (! Hash::check($payload->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid password',
            ], 400);
        }
        $data = (object) [
            'token' => $user->createToken('auth-token')->plainTextToken,
            'user' => UserResource::make($user)
        ];
        // dd($data);

        return AuthResource::make($data);

    }

    public function logout(object $payload)
    {
        $payload->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logout',
        ], Response::HTTP_OK);
    }
}
