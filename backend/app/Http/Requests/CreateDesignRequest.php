<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;




class CreateDesignRequest extends FormRequest
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
            'user_id' => ['required','integer'],
            'name' => ['required','min:1','max:50'],
            'point' => ['integer','min:0','max:500'],
            'contents' => ['required','json']
        ];
    }

    public function messages()
    {
        return[
            'user_id.required' => 'ユーザーIDを入力してください',
            'user_id.integer' => '数値で入力してください',
            'name.required' => 'デザイン名を入力してください',
            'name.max' => '50文字以内で入力してください',
            'point.integer' => '数値で入力してください',
            'point.min' => '0ポイント以上で入力してください',
            'point.max' => '500ポイント以内で入力してください'
        ];
    }

    protected function failedValidation( Validator $validator )
    {
        $response['result'] = $validator->errors()->toArray();
        $response['status']=Response::HTTP_UNPROCESSABLE_ENTITY;

        throw new HttpResponseException(
            response()->json($response['result'],$response['status'])
        );
    }

}


