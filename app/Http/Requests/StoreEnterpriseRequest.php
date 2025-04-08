<?php

namespace App\Http\Requests;

use App\Enums\EnterpriseTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreEnterpriseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'fantasy_name' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18', 'unique:enterprises,cnpj'],
            'email' => ['required', 'email', 'max:255', 'unique:enterprises,email'],
            'price_cp' => ['required', 'numeric', 'min:0'],
            'type' => ['required', new Enum(EnterpriseTypes::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'A razão social é obrigatória',
            'name.max' => 'A razão social não pode ter mais que 255 caracteres',
            'fantasy_name.required' => 'O nome fantasia é obrigatório',
            'fantasy_name.max' => 'O nome fantasia não pode ter mais que 255 caracteres',
            'cnpj.required' => 'O CNPJ é obrigatório',
            'cnpj.max' => 'O CNPJ não pode ter mais que 18 caracteres',
            'cnpj.unique' => 'Este CNPJ já está cadastrado',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'Digite um email válido',
            'email.max' => 'O email não pode ter mais que 255 caracteres',
            'email.unique' => 'Este email já está cadastrado',
            'price_cp.required' => 'O preço CP é obrigatório',
            'price_cp.numeric' => 'O preço CP deve ser um número',
            'price_cp.min' => 'O preço CP não pode ser negativo',
            'type.required' => 'O tipo é obrigatório',
            'type.enum' => 'O tipo selecionado não é válido',
        ];
    }
}
