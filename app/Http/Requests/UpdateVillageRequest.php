<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateVillageRequest extends FormRequest
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
        // Ambil id dari route ketika method-nya update
        $id = $this->route('village');
        return [
            'village_name' => [
                'required',
                Rule::unique('villages')->ignore($id, 'village_id')
            ],
            'village_code' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
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
