<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user),
            ],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:8|confirmed';
        }

        if (!$this->user || !$this->user->hasRole('master')) {
            $rules['enterprise_id'] = 'required|exists:enterprises,id';
        } else {
            $rules['enterprise_id'] = 'nullable|exists:enterprises,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'O email deve ser válido',
            'email.unique' => 'Este email já está em uso',
            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres',
            'password.confirmed' => 'A confirmação da senha não corresponde',
            'enterprise_id.required' => 'A empresa é obrigatória',
            'enterprise_id.exists' => 'A empresa selecionada é inválida',
            'roles.required' => 'Pelo menos um papel deve ser selecionado',
            'roles.*.exists' => 'Um ou mais papéis selecionados são inválidos',
        ];
    }
} 