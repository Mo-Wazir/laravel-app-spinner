<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserSignUpRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function __construct(private UserService $user)
    {
    }

    public function register(UserSignUpRequest $request) : JsonResponse
    {
        $response = $this->user->register($request);
        return $this->response($response);
    }

    public function verifyEmail(Request $request) : JsonResponse
    {
        $response = $this->user->verifyEmail($request);
        return $this->response($response);

    }

    public function resendVerifyEmail(Request $request) : JsonResponse
    {
        $response = $this->user->resendVerifyEmail($request);
        return $this->response($response);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email','password']);
        if(!auth()->attempt($credentials)){
            return $this->error('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

       $user = $request->user();
       return $this->userWithToken($user);
    }

    public function logout(Request $request) : JsonResponse
    {
        if($request->user()->tokens()->delete()){
            return $this->response(['message' => 'Log out successful']);
        }
    }

    public function refresh(Request $request) : JsonResponse
    {
        $response = $this->userWithToken($request->user());
        return $response;
    }

    private function userWithToken($user) : JsonResponse
    {

        $data = [
             'user' => new UserResource($user),
            'token' => $user->createToken('Personal Access Token')->plainTextToken
        ];
        return $this->success('SignIn successful', $data);
    }

}
