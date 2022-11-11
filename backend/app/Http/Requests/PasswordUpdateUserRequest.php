<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException; 
use Illuminate\Http\Response;
use App\Rules\PasswordUpadateRule;

class PasswordUpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' =>[new PasswordUpdateRule,'required','string','min:8','max:60','regex:/(?=.*[a-z)(?=.*[A-Z])(?=.*[0-9])(?=.*[\/\-\_ΔΣΩ])[a-zA-Z0-9]/'],
            'new_password' =>['required','string','min:8','max:60','regex:/(?=.*[a-z)(?=.*[A-Z])(?=.*[0-9])(?=.*[\/\-\_ΔΣΩ])[a-zA-Z0-9]/'],
        ];
    }

    public function messages()
    {
        return[
            'old_password' => '現在のパスワードが違います',
            'old_password.min' => 'パスワードが8文字以上ではありません',
            'old_password.max' => '50文字以内で入力してください',
            'old_password.regex' => 'パスワードが適切ではありません',
            'old_password.required' => '現在のパスワードを入力してください',
            'new_password.required' => '新しいパスワードを入力してください',
            'new_password.min' => 'パスワードが8文字以上ではありません',
            'new_password.max' => '50文字以内で入力してください',
            'new_password.regex' => 'パスワードが適切ではありません',
        ];
    }

    protected function failevalidation(Validator $validator)
    {
        $response['result'] = $validator->errors()->toArray();
        $response['status'] = $Response::HTTP_UNPROCESSABLE_ENTITY;

        throw new HttpResponseException(
            response()->json($response['result'],$response['status'])
        );
    }
}
