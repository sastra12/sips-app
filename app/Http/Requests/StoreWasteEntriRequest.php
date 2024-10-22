<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class StoreWasteEntriRequest extends FormRequest
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
            'waste_organic' => 'required|numeric',
            'waste_anorganic' => 'required|numeric',
            'waste_residue' => 'required|numeric',
            'date_entri' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'waste_organic.required' => 'Data sampah organik tidak boleh kosong',
            'waste_organic.numeric' => 'Data sampah organik harus berupa angka',
            'waste_anorganic.required' => 'Data sampah anorganik tidak boleh kosong',
            'waste_anorganic.numeric' => 'Data sampah anorganik harus berupa angka',
            'waste_residue.required' => 'Data sampah residu tidak boleh kosong',
            'waste_residue.numeric' => 'Data sampah residu harus berupa angka',
            'date_entri.required' => 'Tanggal boleh kosong',
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
