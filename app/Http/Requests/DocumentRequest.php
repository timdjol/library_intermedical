<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'path' => ['required', 'file', 'mimes:pdf,doc,xls,docx,xlsx,txt', 'max:10000'],
            'categories_id' => ['required', 'array', 'min:1'],
            'categories_id.*' => ['integer', 'exists:categories,id'],
            'access' => ['required', 'in:0,1'],
            'status' => ['required', 'in:0,1'],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Поле :attribute обязательно для ввода',
            'mimes' => 'Документ должен быть формата pdf,doc,xls,docx,xlsx,txt',
            'max' => 'Размер файла не должен превышать 10 Мб',
            'file' => 'Загрузите файл',
        ];
    }
}
