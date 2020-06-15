<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Validation\Validator;

class ConvertRequest extends FormRequest
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
        $currenciesList = config('currencies.currencies');
        $currenciesStr = implode('|', $currenciesList);

        return [
            'inputSum' => [
                'required',
                'string',
                'regex:/(^([\d])+(\.)*([\d])*'. $currenciesStr .'$)/'
            ],
            'outputCurrency' => [
                'required',
                'string',
                'regex:/^(as(' . $currenciesStr . ')$)/'
            ],
        ];
    }


    /**
     * @return array
     */
    public function validationData()
    {
        return array_merge($this->request->all(), [
            'inputSum' => Route::input('inputSum'),
            'outputCurrency' => Route::input('outputCurrency'),
        ]);
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}
