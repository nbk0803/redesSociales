<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'name' => "required|string|max:255|regex:/^[\pL\s\-_.']+$/u",
                'username' => 'nullable|string|max:255|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'bio' => 'nullable|string|max:500',
            ];
        }

        return [
            'name' => "required|string|max:255|regex:/^[\pL\s\-_.']+$/u",
            'username' => 'nullable|string|max:255|unique:users,username,'.$this->route('user')->id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->route('user')->id,
            'password' => 'nullable|string|min:8',
            'bio' => 'nullable|string|max:500',
        ];
    }
}
