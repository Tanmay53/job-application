<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateRequest extends FormRequest
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
            'firstName' => ["required", "string"],
            'lastName' => ["required", "string"],
            'email' => ["required", "email", "unique:candidates,email"],
            'gender' => ["required", "in:male,female,other"],
            'profile' => ["nullable", "mimes:png,jpg,jpeg"],
            'skills' => ["required", "array", "min:1", "max:5"],
            'skills.*' => ["required", "exists:skills,id"],
            'locations' => ["required", "array", "min:1", "max:3"],
            'locations.*' => ["required", "exists:locations,id"]
        ];
    }
}
