<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
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
        $types = array_keys(config('login.types'));
        $types = implode(",", $types);
        return [
            "login_type" => ["required", "in:{$types}"],
            "login_key" => ["required", "min:3", "max:255"],
            "password" => [
                "required",
                Password::min(6)
                    // ->letters()
                    // ->numbers()
                    // ->mixedCase()
                    // ->symbols()
                    // ->uncompromised()
            ],
            "remember" => ["nullable", "in:on"],
        ];
    }
}
