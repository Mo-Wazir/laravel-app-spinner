<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\UserVerificationToken;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    /**
     * Undocumented function
     *
     * @param [Request] $request
     * @return array
     */
    public function register($request): array
    {
        return DB::transaction(function () use ($request) {
            $user = User::query()->create($request->all());

            return ['message' => 'User created!', 'data' => ['user' => $user->toArray()]];
        });
    }

    /**
     * Undocumented function
     *
     * @param [Request] $request
     * @return array
     */
    public function verifyEmail($request): array
    {
        $userToken = UserVerificationToken::query()
            ->where('email', $request->email)
            ->where('otp', $request->otp)->first();
        if ($userToken) {

            if (UserVerificationToken::query()->find($userToken->id)->expires_at->lt(\Illuminate\Support\Carbon::now())
                && $user = User::query()->where('email', $userToken->email)) {
                $this->updateVerificationStatus($userToken->id, $user->id);

                return ['message' => 'Success! Email verified', 'data' => ['user' => new UserResource($user)]];
            }
            return ['status' => false, 'message' => 'Token has expired!', 'code' => Response::HTTP_UNAUTHORIZED];
        }
        return ['status' => false, 'message' => 'Invalid Token!', 'code' => Response::HTTP_UNAUTHORIZED];
    }

    /**
     *
     * @param [Request] $request
     * @return array
     */
    public function resendVerifyEmail($request): array
    {
        $user = User::query()->where('email', $request->email)->first();
        $tokenDetail = UserVerificationToken::query()->where('email', $request->email);

        if (($user && empty($user->email_verified_at))) {
            $data = !$tokenDetail ? $this->restoreToken($user->email) :
                ['otp' => $tokenDetail->otp];
            $data['name'] = $user->profile?->first_name;

            Mail::to($user->email)->queue(new VerifyEmail($data));
            return ['message' => 'Email Resent!'];
        }
        return ['status' => false, 'message' => 'Already verified!'];
    }

    /**
     * Undocumented function
     *
     * @param string $email
     * @return array
     */
    private function restoreToken(string $email): array
    {
        $token = rand(100000, 999999);
        $data = ['email' => $email, 'otp' => $token,
            'expires_at' => Carbon::now()->addMinutes(User::TOKEN_EXPIRES_IN)
        ];
        $restoreToken = UserVerificationToken::create($data);

        return $restoreToken->toArray();
    }

    /**
     * Delete token and update verification status(email_verified_at)
     *
     * @param integer $tokenId
     * @param integer $userId
     * @return void
     */
    private function updateVerificationStatus(int $tokenId, int $userId): void
    {
        UserVerificationToken::query()->where('id', $tokenId)->delete();

        User::query()->where('id', $userId)->update(['email_verified_at' => Carbon::now()]);
    }
}
