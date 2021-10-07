<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title'         => 'string|required|max:50',
            'author'        => 'string|required|max:50',
            'translator'    => 'string|required|max:50',
            'status'        => 'string|required',
            'description'   => 'required',
            'date_release'  => 'required',
            'image'         => 'image|mimes:jpg,png,jpeg|max:10000'
        ];
    }
}
