<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FrontEndInterfaceControllerCheck extends FormRequest
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
            'to_user_id'=>'required|integer',
            'project_id'=>'required|integer',
            'back_concent'=>'required',
        ];
    }
    public function messages(){
        return [
            'back_concent.required'=>'反馈内容不能为空',
            'to_user_id.required'=>'接收者id不能为空',
            'to_user_id.integer'=>'接收者id必须是整数',
            'Project_id.required'=>'项目id不能为空',
            'Project_id.integer'=>'项目id必须是整数',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw (new HttpResponseException(response()->json([
            'code'=>100,
            'messages'=>$validator->errors()->all(),
        ],422)));
    }
}
