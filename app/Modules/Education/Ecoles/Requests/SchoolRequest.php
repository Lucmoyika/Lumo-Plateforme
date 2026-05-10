<?php

namespace App\Modules\Education\Ecoles\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $schoolId = (int) $this->route('school');
        $creating = $this->isMethod('post');
        $submoduleKeys = array_keys(config('school_modules.submodules', []));
        $planCodes = array_keys(config('school_modules.plans', []));

        return [
            'name'            => ['required', 'string', 'max:255'],
            'level_types'     => ['required', 'array', 'min:1'],
            'level_types.*'   => ['string', Rule::in(['maternelle', 'primaire', 'secondaire', 'humanites'])],
            'education_submodule' => ['required', 'string', Rule::in($submoduleKeys)],
            'license_plan_code' => ['nullable', 'string', Rule::in($planCodes)],
            'start_trial' => [$creating ? 'required_without:license_plan_code' : 'nullable', 'boolean'],
            'custom_duration_months' => ['nullable', 'integer', 'min:1', 'max:60'],
            'custom_price_cdf' => ['nullable', 'integer', 'min:1'],
            'mobile_access_enabled' => ['nullable', 'boolean'],
            'address'         => ['nullable', 'string', 'max:255'],
            'city'            => ['nullable', 'string', 'max:100'],
            'province'        => ['nullable', 'string', 'max:100'],
            'phone'           => ['nullable', 'string', 'max:20'],
            'email'           => ['nullable', 'email', 'max:255', Rule::unique('schools', 'email')->ignore($schoolId)],
            'website'         => ['nullable', 'url', 'max:255'],
            'director_id'     => [$creating ? 'required_without:create_director' : 'nullable', 'exists:users,id'],
            'status'          => ['nullable', 'string', 'in:active,inactive,pending'],
            'description'     => ['nullable', 'string'],
            // Workflow d'onboarding directeur (créer directement dans l'école)
            'create_director'       => [$creating ? 'required_without:director_id' : 'nullable', 'array'],
            'create_director.name'  => ['required_with:create_director', 'string', 'max:255'],
            'create_director.email' => ['required_with:create_director', 'email', 'max:255', 'unique:users,email'],
            'create_director.phone' => ['nullable', 'string', 'max:20'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->isMethod('post') && !$this->has('start_trial')) {
            $this->merge(['start_trial' => true]);
        }

        // Si le sous-module est fourni, on impose les niveaux RDC correspondants.
        $submodule = $this->input('education_submodule');
        $submoduleLevels = config("school_modules.submodules.{$submodule}.level_types");
        if (is_array($submoduleLevels) && !empty($submoduleLevels)) {
            $this->merge(['level_types' => $submoduleLevels]);
        }

        // Support both legacy type field and new level_types array
        if ($this->has('type') && !$this->has('level_types')) {
            $typeMap = [
                'primary' => 'primaire',
                'middle' => 'secondaire',
                'secondary' => 'secondaire',
                'high' => 'humanites',
                'technical' => 'humanites',
                'private' => 'humanites',
                'maternelle' => 'maternelle',
                'primaire' => 'primaire',
                'secondaire' => 'secondaire',
                'humanites' => 'humanites',
            ];

            $typeValue = $this->input('type');
            if (is_string($typeValue)) {
                $key = strtolower(trim($typeValue));
                $normalizedType = $typeMap[$key] ?? $key;
                $this->merge(['level_types' => [$normalizedType]]);
            }
        }

        // Deduplicate level_types array
        if ($this->has('level_types') && is_array($this->level_types)) {
            $this->merge([
                'level_types' => array_values(array_unique($this->level_types)),
            ]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $planCode = $this->input('license_plan_code');
            $submodule = $this->input('education_submodule');

            if ($planCode) {
                $planSubmodule = config("school_modules.plans.{$planCode}.submodule_key");
                if ($planSubmodule && $submodule && $planSubmodule !== $submodule) {
                    $validator->errors()->add('license_plan_code', 'Le plan choisi ne correspond pas au sous-module sélectionné.');
                }
            }

            // Empêche l'usage gratuit hors période d'essai ou abonnement.
            if ($this->isMethod('post') && !$planCode && !$this->boolean('start_trial')) {
                $validator->errors()->add('license_plan_code', 'Choisissez un plan de licence ou activez l\'essai d\'un mois.');
            }
        });
    }
}
