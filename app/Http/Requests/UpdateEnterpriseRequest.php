<?php

namespace App\Http\Requests;

use App\Enums\EnterpriseTypes;
use App\Models\Enterprise;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateEnterpriseRequest extends FormRequest
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
        $id = request()->route('enterprise');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'fantasy_name' => ['sometimes', 'string', 'max:255'],
            'cnpj' => [
                'sometimes',
                'string',
                'max:18',
                Rule::unique('enterprises', 'cnpj')->ignore($id)
            ],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('enterprises', 'email')->ignore($id)
            ],
            'price_cp' => ['sometimes', 'numeric', 'min:0'],
            'type' => ['sometimes', new Enum(EnterpriseTypes::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'A razão social não pode ter mais que 255 caracteres',
            'fantasy_name.max' => 'O nome fantasia não pode ter mais que 255 caracteres',
            'cnpj.max' => 'O CNPJ não pode ter mais que 18 caracteres',
            'cnpj.unique' => 'Este CNPJ já está cadastrado',
            'email.email' => 'Digite um email válido',
            'email.max' => 'O email não pode ter mais que 255 caracteres',
            'email.unique' => 'Este email já está cadastrado',
            'price_cp.numeric' => 'O preço CP deve ser um número',
            'price_cp.min' => 'O preço CP não pode ser negativo',
            'type.enum' => 'O tipo selecionado não é válido',
        ];
    }
}
