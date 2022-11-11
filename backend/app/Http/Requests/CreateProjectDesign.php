<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException; 
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;


class CreateProjectDesign extends FormRequest
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
            'design_id' => [
                'required',
                Rule::unique('project_design')->ignore($this->input('id'))->where(function($query){
                    $query->where('project_id',$this->input('project_id'));
                }),
            ],
        ];
    }


    public function messages()
    {
        return[
            'project_id.required' => 'プロジェクトIDを入力してください',
            'design_id.required' => 'デザインIDを入力してください',
            'design_id.unique' => '重複してます',
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
