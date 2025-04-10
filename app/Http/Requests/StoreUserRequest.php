<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults(), 'confirmed'],
            'enterprise_id' => ['sometimes', 'nullable', 'exists:enterprises,id'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,name'],
            'phone_numbers' => ['sometimes', 'array'],
            'phone_numbers.*.phone_number' => ['sometimes', 'string', 'max:20'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
            'enterprise_id' => 'empresa',
            'roles' => 'perfis',
            'roles.*' => 'perfil',
            'phone_numbers' => 'telefones',
            'phone_numbers.*.phone_number' => 'número de telefone',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório',
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'Informe um e-mail válido',
            'email.unique' => 'Este e-mail já está em uso',
            'password.required' => 'A senha é obrigatória',
            'password.confirmed' => 'As senhas não coincidem',
            'roles.required' => 'Selecione pelo menos um perfil',
        ];
    }
} 