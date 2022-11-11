<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException; 
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;


class CreateUserDesignRequest extends FormRequest
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
            'user_id' => 'required',
            'design_id' => [
                'required',
                Rule::unique('user_design')->ignore($this->input('id'))->where(function($query){
                    $query->where('user_id',$this->input('user_id'));
                }),
            ],
        ];
    }

    public function messages()
    {
        return[
            'user_id.request' => 'ユーザーIDを入力してください',
            'design_id.required' => 'デザインIDを入力してください',
            'design_id.unique' => '登録されているユーザーIDとデザインIDです',
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
