<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConstructionRequest extends FormRequest
{
    public function authorize()
    {
        $user = $this->user();
        $enterpriseId = $this->input('enterprise_id');

        return $user->hasRole('master') || 
               ($user->enterprise_id === $enterpriseId && $user->hasRole('gestor'));
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'enterprise_id' => 'required|exists:enterprises,id',
            'address_id' => 'required|exists:addresses,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'enterprise_id.required' => 'A empresa é obrigatória',
            'enterprise_id.exists' => 'A empresa selecionada é inválida',
            'address_id.required' => 'O endereço é obrigatório',
            'address_id.exists' => 'O endereço selecionado é inválido',
        ];
    }
} 