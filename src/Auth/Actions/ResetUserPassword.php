<?php

namespace Jegex\Koboi\Auth\Actions;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Jegex\Koboi\Auth\PasswordValidationRules;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  CanResetPassword&Model  $user
     * @param  array<string, string>  $input
     */
    public function reset(
        CanResetPassword $user,
        #[\SensitiveParameter] array $input
    ): void {
        Validator::make($input, [
            'password' => $this->passwordWithConfirmedRules(),
        ])->validate();

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
