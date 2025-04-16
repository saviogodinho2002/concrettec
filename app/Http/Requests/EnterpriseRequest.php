<?php

namespace App\Http\Requests;

use App\Enums\EnterpriseTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class EnterpriseRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRole('master');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => ['required', new Enum(EnterpriseTypes::class)],

            'cnpj' => [
                'required',
                'string',
                'size:18',
                Rule::unique('enterprises')->ignore($this->enterprise),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'type.required' => 'O tipo é obrigatório',
            'type.in' => 'O tipo deve ser concreteira ou construtora',
            'cnpj.required' => 'O CNPJ é obrigatório',
            'cnpj.size' => 'O CNPJ deve ter 14 dígitos',
            'cnpj.unique' => 'Este CNPJ já está cadastrado',
        ];
    }
}
