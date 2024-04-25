<?php

namespace App\Http\Requests\Order;

use App\Models\Meal;
use Illuminate\Foundation\Http\FormRequest;

class Step1Request extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'meal' => ['required', 'exists:' . Meal::class . ',id'],
            'number_people' => ['required', 'numeric', 'min:1', 'max:10'],
        ];
    }
}
