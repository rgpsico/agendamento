<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'cpfCnpj' => 'required|string|max:14',
            'phone' => 'nullable|string',
            'mobilePhone' => 'required|string',
            'address' => 'required|string',
            'addressNumber' => 'required|string',
            'complement' => 'nullable|string',
            'province' => 'required|string',
            'postalCode' => 'required|string',
            'externalReference' => 'nullable|string',
            'notificationDisabled' => 'boolean',
            'additionalEmails' => 'nullable|string',
            'municipalInscription' => 'nullable|string',
            'stateInscription' => 'nullable|string',
            'observations' => 'nullable|string',
            'groupName' => 'nullable|string',
        ];
    }
}
