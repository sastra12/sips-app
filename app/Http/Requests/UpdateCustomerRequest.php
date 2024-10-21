<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateCustomerRequest extends FormRequest
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
            'customer_name' => 'required',
            'customer_address' => 'required',
            'customer_neighborhood' => 'required|numeric',
            'customer_community_association' => 'required|numeric',
            'rubbish_fee' => 'required|numeric',
            'customer_status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'customer_name.required' => 'Nama pelanggan tidak boleh kosong',
            'customer_address.required' => 'Alamat pelanggan tidak boleh kosong',
            'customer_neighborhood.required' => 'Data RT tidak boleh kosong',
            'customer_neighborhood.numeric' => 'Data RT harus berupa angka',
            'customer_community_association.required' => 'Data RW tidak boleh kosong',
            'customer_community_association.numeric' => 'Data RW harus berupa angka',
            'rubbish_fee.required' => 'Data iuran tidak boleh kosong',
            'rubbish_fee.numeric' => 'Data iuran harus berupa angka',
            'customer_status.required' => 'Data status tidak boleh kosong',
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
