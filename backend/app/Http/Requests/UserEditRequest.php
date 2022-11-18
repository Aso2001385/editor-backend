<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;


class UserEditRequest extends FormRequest
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
            'class' => ['integer','min:0','max:1'],
            'name' => ['required','string','max:50'],
            'email' => ['required','email','max:50','unique:users'],
        ];
    }

    public function messages()
    {
        return[
            'class.integer' => '数値で入力してください',
            'class.min' => '1か0で入力してください',
            'class.max' => '1か0で入力してください',
            'name.required' => '名前を入力してください',
            'name.max' => '50文字以内で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => '有効なメールアドレスではありません',
            'email.max' => '50文字以内で入力してください',
            'email.unique' => '登録済みのメールアドレスです',
            ];
    }

    protected function failevalidation(Validator $validator)
    {
        $response['result'] = $validator->errors()->toArray();
        $response['status'] = Response::HTTP_UNPROCESSABLE_ENTITY;

        throw new HttpResponseException(
            response()->json($response['result'],$response['status'])
        );
    }
}
