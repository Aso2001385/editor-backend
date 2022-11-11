<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException; 
use Illuminate\Http\Response;


class UserRegisterRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'email' => ['required','email','max:50','unique:users'],
            'password' => ['required','string','min:8','max:60','regex:/(?=.*[a-z)(?=.*[A-Z])(?=.*[0-9])(?=.*[\/\-\_ΔΣΩ])[a-zA-Z0-9]/'],
        ];
    }

    public function message()
    {
        return[
            'name.required' => '名前を入力してください',
            'name.max' => '50文字以内で入力してください',
            'email.required' => '有効のメールアドレスではありません',
            'email.email' => '有効なメールアドレスではありません',
            'email.max' => '50文字で入力してください',
            'email.unique' => '登録済みのメールアドレスです',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードが8文字以上ではありません',
            'password.max' => '60文字以内で入力してください',
            'password.regex' => 'パスワードが適切ではありません',
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
