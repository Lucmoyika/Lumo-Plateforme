<?php

namespace App\Modules\Education\Ecoles\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Education\Ecoles\Models\Grade;
use App\Modules\Education\Ecoles\Models\SchoolClass;
use App\Modules\Education\Ecoles\Models\Student;
use App\Modules\Education\Ecoles\Requests\GradeRequest;
use App\Modules\Education\Ecoles\Services\GradeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function __construct(private readonly GradeService $gradeService) {}

    /**
     * List grades by class_id or student_id and optional term.
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'class_id' => ['nullable', 'exists:school_classes,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'term' => ['nullable', 'string', 'max:50'],
            'period' => ['nullable', 'string', 'max:50'],
        ]);

        $term = $request->get('term') ?: $request->get('period');
        $schoolId = (int) ($request->route('school') ?? 0);
        $user = auth()->user();

        // Les élèves ne peuvent voir que leurs propres notes
        if ($user->hasRole('student') && $request->filled('student_id')) {
            $student = Student::query()->find((int) $request->get('student_id'));
            if (!$student || $student->user_id !== $user->id) {
                return $this->errorResponse('Vous ne pouvez voir que vos propres notes.', [], 403);
            }
        }

        // Les parents ne peuvent voir que les notes de leurs enfants
        if ($user->hasRole('parent') && $request->filled('student_id')) {
            $student = Student::query()->find((int) $request->get('student_id'));
            if (!$student || $student->parent_id !== $user->id) {
                return $this->errorResponse('Vous ne pouvez voir que les notes de vos enfants.', [], 403);
            }
        }

        if ($request->filled('student_id')) {
            if ($schoolId > 0) {
                $student = Student::query()->find((int) $request->get('student_id'));
                if (!$student || (int) $student->school_id !== $schoolId) {
                    return $this->errorResponse('Etudiant hors périmètre établissement.', [], 403);
                }
            }

            $data = $this->gradeService->getByStudent((int) $request->get('student_id'), $term);
            return $this->successResponse($data, 'Notes récupérées.');
        }

        if ($request->filled('class_id')) {
            // Les enseignants ne peuvent voir que les notes de LEURS classes
            if ($user->hasRole('teacher')) {
                // À implémenter: vérifier que teacher est enseignant DE cette classe
            }

            if ($schoolId > 0) {
                $class = SchoolClass::query()->find((int) $request->get('class_id'));
                if (!$class || (int) $class->school_id !== $schoolId) {
                    return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
                }
            }

            $data = $this->gradeService->getByClass((int) $request->get('class_id'), $term);
            return $this->successResponse($data, 'Notes récupérées.');
        }

        return $this->errorResponse('Fournissez class_id ou student_id.', [], 422);
    }

    /**
     * Store or update a grade entry.
     */
    public function store(GradeRequest $request): JsonResponse
    {
        // Vérifier que l'utilisateur a le droit de créer une note
        if (!auth()->user()->can('grades.create') && !auth()->user()->hasRole(['school_admin', 'teacher'])) {
            return $this->errorResponse('Vous n\'avez pas la permission de créer une note.', [], 403);
        }

        $data = $request->validated();
        $schoolId = (int) ($request->route('school') ?? 0);

        if ($schoolId > 0) {
            $class = SchoolClass::query()->find((int) $data['class_id']);
            $student = Student::query()->find((int) $data['student_id']);

            if (!$class || (int) $class->school_id !== $schoolId) {
                return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
            }

            if (!$student || (int) $student->school_id !== $schoolId) {
                return $this->errorResponse('Etudiant hors périmètre établissement.', [], 403);
            }

            if ((int) $student->class_id !== (int) $class->id) {
                return $this->errorResponse('L\'étudiant ne dépend pas de la classe fournie.', [], 422);
            }
        }

        $grade = $this->gradeService->storeOrUpdate($data);

        return $this->successResponse($grade, 'Note enregistrée.', 201);
    }

    /**
     * Update an existing grade.
     */
    public function update(GradeRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $schoolId = (int) ($request->route('school') ?? 0);

        if ($schoolId > 0) {
            $existing = Grade::query()->find($id);
            if (!$existing) {
                return $this->errorResponse('Note introuvable.', [], 404);
            }

            $existingClass = SchoolClass::query()->find((int) $existing->class_id);
            if (!$existingClass || (int) $existingClass->school_id !== $schoolId) {
                return $this->errorResponse('Note hors périmètre établissement.', [], 403);
            }

            $class = SchoolClass::query()->find((int) $data['class_id']);
            $student = Student::query()->find((int) $data['student_id']);

            if (!$class || (int) $class->school_id !== $schoolId) {
                return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
            }

            if (!$student || (int) $student->school_id !== $schoolId) {
                return $this->errorResponse('Etudiant hors périmètre établissement.', [], 403);
            }

            if ((int) $student->class_id !== (int) $class->id) {
                return $this->errorResponse('L\'étudiant ne dépend pas de la classe fournie.', [], 422);
            }
        }

        $grade = $this->gradeService->update($id, $data);

        return $this->successResponse($grade, 'Note mise à jour.');
    }

    /**
     * Get report card (bulletin) for a student for a given period.
     */
    public function getBulletin(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'term'       => ['nullable', 'string'],
            'period'     => ['nullable', 'string'],
        ]);

        $term = $request->get('term') ?: $request->get('period');
        if (!$term) {
            return $this->errorResponse('Le champ term est requis.', [], 422);
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0) {
            $student = Student::query()->find((int) $request->get('student_id'));
            if (!$student || (int) $student->school_id !== $schoolId) {
                return $this->errorResponse('Etudiant hors périmètre établissement.', [], 403);
            }
        }

        $bulletin = $this->gradeService->getBulletin(
            (int) $request->get('student_id'),
            $term
        );

        return $this->successResponse($bulletin, 'Bulletin récupéré.');
    }

    /**
     * Get class-level grade report for a period.
     */
    public function getReport(Request $request): JsonResponse
    {
        $request->validate([
            'class_id' => ['required', 'exists:school_classes,id'],
            'term'     => ['nullable', 'string'],
            'period'   => ['nullable', 'string'],
        ]);

        $term = $request->get('term') ?: $request->get('period');
        if (!$term) {
            return $this->errorResponse('Le champ term est requis.', [], 422);
        }

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0) {
            $class = SchoolClass::query()->find((int) $request->get('class_id'));
            if (!$class || (int) $class->school_id !== $schoolId) {
                return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
            }
        }

        $report = $this->gradeService->getReport(
            (int) $request->get('class_id'),
            $term
        );

        return $this->successResponse($report, 'Rapport généré.');
    }

    /**
     * Get aggregate statistics for a class.
     */
    public function getStats(Request $request): JsonResponse
    {
        $request->validate(['class_id' => ['required', 'exists:school_classes,id']]);

        $schoolId = (int) ($request->route('school') ?? 0);
        if ($schoolId > 0) {
            $class = SchoolClass::query()->find((int) $request->get('class_id'));
            if (!$class || (int) $class->school_id !== $schoolId) {
                return $this->errorResponse('Classe hors périmètre établissement.', [], 403);
            }
        }

        $stats = $this->gradeService->getStats((int) $request->get('class_id'));

        return $this->successResponse($stats, 'Statistiques calculées.');
    }
}
