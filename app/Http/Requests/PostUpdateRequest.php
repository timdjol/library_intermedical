<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $post = $this->route('admin_post') ?? $this->route('agent_post');

        return [
            'title' => ['required', 'string', 'min:3', 'max:255', Rule::unique('posts', 'title')->ignore($post?->id)],
            'description' => ['required', 'string', 'min:5'],
            'categories_id' => ['required', 'array', 'min:1'],
            'categories_id.*' => ['integer', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'status' => ['required', 'in:0,1'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Поле :attribute обязательно для ввода',
            'min' => 'Поле :attribute должно иметь минимум :min символов',
            'mimes' => 'Изображение должно быть формата jpeg, png, jpg, gif или webp',
            'max' => 'Размер изображения не должен превышать 2 Мб',
            'image' => 'Загрузите изображение',
            'unique' => 'Поле :attribute должно быть уникальным',
        ];
    }
}
