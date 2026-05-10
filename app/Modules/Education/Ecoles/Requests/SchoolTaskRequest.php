<?php

namespace App\Modules\Education\Ecoles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchoolTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $updating = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'title' => [$updating ? 'sometimes' : 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'priority' => ['nullable', 'string', 'in:low,medium,high,urgent'],
            'status' => ['nullable', 'string', 'in:todo,in_progress,done,blocked,cancelled'],
            'due_date' => ['nullable', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ];
    }
}
