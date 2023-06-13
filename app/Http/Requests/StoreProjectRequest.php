<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|unique:projects|max:150|min:3',
            'image' => 'nullable|max:255',
            'description' => 'nullable',
            'type_id' => 'nullable|exists:types,id'
        ];
    }
    public function messages()
    {
        return [
            'title.required' => 'A title is required.',
            'title.unique:projects' => 'There is another project with this name.',
            'title.max' => 'Title can be up to :max characters.',
            'title.min' => 'Title have to be at least :min characters.',
            'image.nullable' => 'An image is required, you can use a saved image or an URL link.',
            'image.max' => 'The URL can be up to :max characters',
            'description.nullable' => 'a description is required.',
        ];
    }
}
