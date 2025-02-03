<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\ForgotPasswordService;
use Illuminate\Http\JsonResponse;

class ForgetPasswordController extends Controller
{

    public function __construct(protected ForgotPasswordService $forgotPassword)
    {
    }

    public function forgotPassword(ForgotPasswordRequest $request) : JsonResponse
    {
        $response = $this->forgotPassword->sendForgotPassword($request);
        return $this->response($response);
    }

    public function resetPassword(ResetPasswordRequest $request) : JsonResponse
    {
        $response = $this->forgotPassword->resetPassword($request);
        return $this->response($response);
    }
}
