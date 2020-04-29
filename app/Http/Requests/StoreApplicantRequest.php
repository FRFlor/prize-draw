<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicantRequest extends FormRequest
{
    public function authorize()
    {
        // A guest must provide an email to be authorized to store a new name to the pool
        if (auth()->guest()) {
            return $this->has(['name', 'email']);
        }

        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'sometimes|required|email|unique:applicants'
        ];
    }
}
