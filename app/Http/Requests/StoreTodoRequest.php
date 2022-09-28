<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'task_priority' => 'required|integer',
            'due_at' => [
                function ($attribute, $value, $fail) {
                    if(strtotime($value) < strtotime(date('Y-m-d'))) {
                        $fail("Due Date must be equal or greater than current date");
                    }
                },
            ],
        ];
    }
}
