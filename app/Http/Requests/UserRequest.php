<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Summary of failedValidation
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 422));
    }

    /**
     * Summary of rules
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id ?? $this->user()->id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$userId,
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

    /**
     * Summary of messages
     *
     * @return string[]
     */
    public function messages(): string|array
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
