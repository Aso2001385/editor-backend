<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;
use App\Models\User.php;

class PasswordUpadateRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $hash = User::find($value[0],['password']);
        $check=sodium_crypto_pwhash_str_verify(string $hash,string $value[1]);
        return accepted_if($check,true);
        }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'パスワードが間違っています';
    }
}
