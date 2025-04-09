<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConstructionRequest extends FormRequest
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
            'enterprise_id' => ['required', 'exists:enterprises,id'],
            'address' => ['required', 'array'],
            'address.street' => ['required', 'string', 'max:255'],
            'address.number' => ['nullable', 'string', 'max:20'],
            'address.neighborhood' => ['nullable', 'string', 'max:255'],
            'address.complement' => ['nullable', 'string', 'max:255'],
            'address.cep' => ['nullable', 'string', 'max:10'],
            'address.city' => ['required', 'string', 'max:100'],
            'address.uf' => ['required', 'string', 'size:2'],
            'address.latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'address.longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da obra é obrigatório',
            'name.max' => 'O nome da obra não pode ter mais que 255 caracteres',
            'enterprise_id.required' => 'A empresa é obrigatória',
            'enterprise_id.exists' => 'A empresa selecionada é inválida',
            'address.required' => 'O endereço é obrigatório',
            'address.street.required' => 'A rua é obrigatória',
            'address.street.max' => 'A rua não pode ter mais que 255 caracteres',
            'address.number.max' => 'O número não pode ter mais que 20 caracteres',
            'address.neighborhood.max' => 'O bairro não pode ter mais que 255 caracteres',
            'address.complement.max' => 'O complemento não pode ter mais que 255 caracteres',
            'address.cep.max' => 'O CEP não pode ter mais que 10 caracteres',
            'address.city.required' => 'A cidade é obrigatória',
            'address.uf.required' => 'O estado (UF) é obrigatório',
            'address.uf.size' => 'O estado (UF) deve ter 2 caracteres',
        ];
    }
}
