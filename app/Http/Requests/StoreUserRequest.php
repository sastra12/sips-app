<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreUserRequest extends FormRequest
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
        // dapatkan input role usernya
        $role_user = $this->role_user;
        $rules = [
            'name' => 'required',
            'role_user' => 'required'
        ];

        if ($role_user == 2) {
            $rules['waste_name'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'role_user.required' => 'Role user tidak boleh kosong',
            'waste_name.required' => 'TPS3R tidak boleh kosong'
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
