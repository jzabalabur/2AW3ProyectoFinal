<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWebRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'url' => 'required|url',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'El nombre de la web es obligatorio.',
            'name.string' => 'El nombre de la web debe ser una cadena de texto.',
            'name.max' => 'El nombre de la web no puede tener más de 255 caracteres.',
            'url.required' => 'La URL es obligatoria.',
            'url.url' => 'Por favor, introduce una URL válida.',
        ];
    }
}
