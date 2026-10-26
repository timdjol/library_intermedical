<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('admin_category');

        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'parent_id' => [
                'required',
                'integer',
                'min:0',
                Rule::when((int) $this->input('parent_id') > 0, ['exists:categories,id']),
                function (string $attribute, mixed $value, \Closure $fail) use ($category): void {
                    if ($category && (int) $value === $category->id) {
                        $fail('Категория не может быть родителем самой себя');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Поле :attribute обязательно для ввода',
            'min' => 'Поле :attribute должно иметь минимум :min символов',
        ];
    }
}
