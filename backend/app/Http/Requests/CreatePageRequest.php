<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException; 
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;


class CreatePageRequest extends FormRequest
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
            'project_id' => ['required'],
            'number' => [
                'required',
                Rule::unique('peges')->ignore($this->input('project_id'))->where(function($query){
                    $query->where('project_id',$this->input('project_id'));
                }),
            ],
            'title' => ['max:50'],
        ];
    }

    public function messages()
    {
        return[
            'project_id.required' => 'プロジェクトIDを入力してください',
            'number.required' => '入力してください',
            'title.max' => '50文字以内で入力してください'
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
