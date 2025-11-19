<?php

namespace App\Services\External;

use App\Models\User;
use App\Traits\OtpTrait;

class AuthService
{
    use OtpTrait;

    public function validateOtp($phone, $phoneCode, $otp)
    {
        $user = User::where([
            'phone_code' => $phoneCode,
            'phone' => $phone,
        ])->first();

        if (!$user) {
            return trans('translation.User not found');
        }

        $has_error = $this->verifyOtp($otp, $user);
        if (!is_string($has_error)) {
            $data = $has_error->getData(true);
            return $data['message'];
        }

        return true;
    }
}
