<?php 

// app/Http/Requests/CreateSubaccountRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubaccountRequest extends FormRequest
{
    public function rules()
    {
        return [
            'professor_id' => 'required|exists:professores,id',
            'cpfCnpj' => 'required|string|max:18',
            'phone' => 'required|string|max:20',
            'mobilePhone' => 'required|string|max:20',
            'birthDate' => 'required|date',
            'incomeValue' => 'required|numeric|min:0',
            'address' => 'required|string|max:255',
            'addressNumber' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'postalCode' => 'required|string|max:9',
            'personType' => 'required|in:FISICA,JURIDICA',
            'name' => 'nullable|string|max:255' // Opcional, pois pode pegar do usuário
        ];
    }
}