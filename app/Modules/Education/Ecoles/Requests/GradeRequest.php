<?php

namespace App\Modules\Education\Ecoles\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'class_id'   => ['required', 'exists:school_classes,id'],
            'subject'    => ['required', 'string', 'max:100'],
            'academic_year' => ['required', 'string', 'max:20'],
            'term'       => ['required', 'string', 'max:50'],
            'score'      => ['required', 'numeric', 'min:0'],
            'max_score'  => ['nullable', 'numeric', 'min:1', 'max:100'],
            'exam_type'  => ['nullable', 'string', 'in:exam,quiz,homework,participation'],
            'notes'      => ['nullable', 'string', 'max:500'],
            'teacher_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $score = $this->input('score');
            $maxScore = $this->input('max_score');

            if ($score !== null && $maxScore !== null && is_numeric($score) && is_numeric($maxScore) && (float) $score > (float) $maxScore) {
                $validator->errors()->add('score', 'La note ne peut pas dépasser le score maximum.');
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $score = $this->input('score', $this->input('value'));
        $term = $this->input('term', $this->input('period'));
        $examType = $this->input('exam_type', $this->input('type'));
        $notes = $this->input('notes', $this->input('comment'));

        $this->merge([
            'score' => $score,
            'term' => $term,
            'exam_type' => $examType,
            'notes' => $notes,
            'academic_year' => $this->input('academic_year') ?? date('Y') . '-' . (date('Y') + 1),
            'max_score' => $this->input('max_score') ?? 20,
        ]);
    }
}
