<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->route('user'),
            'password' => $this->isMethod('post') ? 'required|string|min:8' : 'sometimes|nullable|string|min:8',
            'role' => 'required|in:parent,professional,admin',
            'phone' => 'nullable|string|max:15',
            'cep' => 'nullable|string|size:8',
            'address' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:10',
            'complement' => 'nullable|string|max:50',
            'neighborhood' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|size:2',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Forneça um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'role.required' => 'O papel é obrigatório.',
            'role.in' => 'O papel deve ser um dos seguintes: parent, professional, admin.',
            'cep.size' => 'O CEP deve ter 8 caracteres.',
            'state.size' => 'O estado deve ter 2 caracteres.',
        ];
    }
}
