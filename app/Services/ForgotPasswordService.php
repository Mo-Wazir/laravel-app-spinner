<?php
declare(strict_types=1);

namespace App\Services;

use App\Mail\ResetPasswordEmail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ForgotPasswordService
{
    /**
     * Undocumented function
     *
     * @param [Request] $request
     * @return array
     */
    public function sendForgotPassword($request): array
    {
        if ($this->ifTokenExists($request->email) && $user = User::query()->whereEmail($request->email)) {
            $data = [
                'token' => rand(100000, 999999),
                'email' => $user->email,
                'name' => ucfirst($user->profile->first_name)
            ];

            if (DB::table('password_reset_tokens')->insert(['email' => $data['email'], 'token' => $data['token']])) {

                Mail::to($user->email)->queue(new ResetPasswordEmail($data));
                return ['message' => 'Check your email for the verification link/token'];
            }
        }
        return ['status' => false, 'message' => 'Invalid Email', 'code' => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY];
    }

    /**
     * Undocumented function
     *
     * @param [Request] $request
     * @return array
     */
    public function resetPassword($request): array
    {
        $data = ['password' => Hash::make($request->password)];
        $id = User::query()->where('email', $request->email)->id;
        if (User::query()->where('id', $id)->update($data) && DB::table('password_reset_tokens')->whereEmail($request->email)->delete()) {
            return ['message' => 'Congrats! Your password has been reset, proceed to sign in'];
        }
        return ['status' => false, 'message' => 'Failed to reset, request to reset password again!'];
    }

    /**
     * Undocumented function
     *
     * @param [type] $email
     * @return bool | int
     */
    private function ifTokenExists($email): bool|int
    {
        if (DB::table('password_reset_tokens')->where('email', $email)->exists()) {
            return DB::table('password_reset_tokens')->whereEmail($email)->delete();
        }
        return true;
    }
}
