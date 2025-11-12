<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $expense = $this->route('expense');
        return $this->user()->can('update', $expense);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'title' => 'sometimes|string|max:255',
            'amount' => 'sometimes|numeric|min:0',
            'spent_at' => 'sometimes|date',
            'category' => 'sometimes|in:MEAL,TRAVEL,HOTEL,OTHER'
        ];
    }
}
