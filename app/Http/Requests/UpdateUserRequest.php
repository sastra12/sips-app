<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateUserRequest extends FormRequest
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
        $role_user = $this->role;
        $rules = [
            'role' => 'required'
        ];

        if ($role_user == 2) {
            $rules['waste_name'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'waste_name.required' => 'TPS3R tidak boleh kosong',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => 'Error',
                'errors' => $validator->messages()
            ])
        );
    }
}
