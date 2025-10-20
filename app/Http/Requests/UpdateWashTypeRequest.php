<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWashTypeRequest extends FormRequest
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
            'name'                 => ['sometimes', 'array'],
            'name.en'              => ['sometimes', 'string', 'max:255'],
            'name.ar'              => ['sometimes', 'string', 'max:255'],
            'price'                => ['sometimes', 'numeric', 'min:0'],
            'description'          => ['nullable', 'array'],
            'description.en'       => ['nullable', 'string', 'max:255'],
            'description.ar'       => ['nullable', 'string', 'max:255'],
        ];
    }
}
