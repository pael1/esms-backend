<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Interface\Service\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function login(AuthRequest $request)
    {
        return $this->authService->login($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }
}
