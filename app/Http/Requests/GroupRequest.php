<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
                'name' => 'required|string|max:255|regex:/^[\pL\pN\s\-_.]+$/u|unique:groups,name',
                'description' => 'required|string|max:1000',
                'privacy' => 'required|in:public,private,secret',
            ];
        }

        return [
            'name' => 'required|string|max:255|regex:/^[\pL\pN\s\-_.]+$/u|unique:groups,name,'.$this->route('group')->id,
            'description' => 'required|string|max:1000',
            'privacy' => 'required|in:public,private,secret',
            'status' => 'required|in:active,inactive',
        ];
    }
}
