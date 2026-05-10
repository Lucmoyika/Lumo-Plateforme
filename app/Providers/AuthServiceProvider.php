<?php

namespace App\Providers;

use App\Models\User;
use App\Modules\Education\Ecoles\Models\Attendance;
use App\Modules\Education\Ecoles\Models\Grade;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Ecoles\Models\Student;
use App\Policies\AttendancePolicy;
use App\Policies\GradePolicy;
use App\Policies\SchoolPolicy;
use App\Policies\StudentPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Les mappages de policies du modèle.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        School::class => SchoolPolicy::class,
        Student::class => StudentPolicy::class,
        Grade::class => GradePolicy::class,
        Attendance::class => AttendancePolicy::class,
    ];

    /**
     * Enregistrer les services d'authentification/autorisation.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
