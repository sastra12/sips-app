<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreVillageRequest extends FormRequest
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
            'village_name' => 'required|unique:villages,village_name',
            'village_code' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return  [
            'village_name.required' => 'Nama desa tidak boleh kosong',
            'village_name.unique' => 'Nama desa sudah ada, silakan pilih yang lain',
            'village_code.required' => 'Kode desa tidak boleh kosong',
            'village_code.numeric' => 'Kode desa harus berupa angka',
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
