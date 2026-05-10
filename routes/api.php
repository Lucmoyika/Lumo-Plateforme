<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Core\Controllers\AuthController;
use App\Modules\Core\Controllers\VideoController;
use App\Modules\Core\Controllers\SettingController;
use App\Modules\Analytics\Controllers\AnalyticsController;
use App\Modules\Education\Ecoles\Controllers\SchoolController;
use App\Modules\Education\Ecoles\Controllers\SchoolClassController;
use App\Modules\Education\Ecoles\Controllers\StudentController;
use App\Modules\Education\Ecoles\Controllers\TeacherController;
use App\Modules\Education\Ecoles\Controllers\AttendanceController;
use App\Modules\Education\Ecoles\Controllers\GradeController;
use App\Modules\Education\Ecoles\Controllers\SchoolTaskController;
use App\Modules\Education\Ecoles\Controllers\SchoolMemberPermissionController;
use App\Modules\Education\Ecoles\Controllers\SchoolYearController;
use App\Modules\Education\Universites\Controllers\UniversityController;
use App\Modules\Education\Universites\Controllers\FacultyController;
use App\Modules\Education\Universites\Controllers\DepartmentController;
use App\Modules\Education\Universites\Controllers\CourseController;
use App\Modules\Education\Universites\Controllers\UniversityStudentController;
use App\Modules\Education\Universites\Controllers\UniversityGradeController;
use App\Modules\Education\Universites\Controllers\ThesisController;
use App\Modules\Entreprises\Controllers\CompanyController;
use App\Modules\Entreprises\Controllers\JobOfferController as EntreprisesJobOfferController;
use App\Modules\Emploi\Controllers\JobOfferController as EmploiJobOfferController;
use App\Modules\Emploi\Controllers\JobApplicationController;
use App\Modules\Ecommerce\Controllers\ProductController;
use App\Modules\Ecommerce\Controllers\ProductCategoryController;
use App\Modules\Ecommerce\Controllers\OrderController;
use App\Modules\Paiement\Controllers\PaymentController;
use App\Modules\Paiement\Controllers\WalletController;
use App\Modules\Logistique\Controllers\ShipmentController;
use App\Modules\Communication\Controllers\ConversationController;
use App\Modules\Communication\Controllers\MessageController;

// School Sub-modules (Maternelle, Primaire, Secondaire, Humanités)
// These are included at the end to separate concerns

// Public auth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refreshToken']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/password', [AuthController::class, 'changePassword']);
    });

    // Schools
    Route::prefix('schools')->group(function () {
        Route::get('/catalog/submodules', [SchoolController::class, 'submodulesCatalog'])->middleware('token.permission:schools.view');
        Route::get('/catalog/plans', [SchoolController::class, 'licensePlansCatalog'])->middleware('token.permission:schools.view');
        Route::get('/', [SchoolController::class, 'index'])->middleware('token.permission:schools.view');
        Route::post('/', [SchoolController::class, 'store'])->middleware('token.permission:schools.create');
        Route::get('/available-directors', [SchoolController::class, 'availableDirectors'])->middleware('token.permission:schools.create');
        Route::get('/{school}', [SchoolController::class, 'show'])->middleware(['token.permission:schools.view', 'token.school_scope']);
        Route::get('/{school}/license-status', [SchoolController::class, 'licenseStatus'])->middleware(['token.permission:schools.view', 'token.school_scope']);
        Route::put('/{school}', [SchoolController::class, 'update'])->middleware(['token.permission:schools.update', 'token.school_scope']);
        Route::delete('/{school}', [SchoolController::class, 'destroy'])->middleware(['token.permission:schools.delete', 'token.school_scope']);
        Route::prefix('/{school}')->middleware('token.school_scope')->group(function () {
            Route::get('/members', [SchoolMemberPermissionController::class, 'index'])->middleware('token.permission:schools.delegate');
            Route::post('/members/{user}/delegations', [SchoolMemberPermissionController::class, 'store'])->middleware('token.permission:schools.delegate');
            Route::put('/members/{user}/permissions', [SchoolMemberPermissionController::class, 'sync'])->middleware('token.permission:schools.delegate');
            Route::delete('/members/{user}/delegations/{delegation}', [SchoolMemberPermissionController::class, 'revoke'])->middleware('token.permission:schools.delegate');
            Route::get('/classes', [SchoolClassController::class, 'index'])->middleware('token.permission:school-classes.view');
            Route::post('/classes', [SchoolClassController::class, 'store'])->middleware('token.permission:school-classes.create');
            Route::get('/classes/{class}', [SchoolClassController::class, 'show'])->middleware('token.permission:school-classes.view');
            Route::put('/classes/{class}', [SchoolClassController::class, 'update'])->middleware('token.permission:school-classes.update');
            Route::delete('/classes/{class}', [SchoolClassController::class, 'destroy'])->middleware('token.permission:school-classes.delete');
            Route::get('/classes/{class}/schedule', [SchoolClassController::class, 'getSchedule'])->middleware('token.permission:school-classes.view');
            Route::put('/classes/{class}/schedule', [SchoolClassController::class, 'updateSchedule'])->middleware('token.permission:school-classes.update');
            Route::get('/students', [StudentController::class, 'index'])->middleware('token.permission:students.view');
            Route::post('/students', [StudentController::class, 'store'])->middleware('token.permission:students.create');
            Route::post('/students/import', [StudentController::class, 'importStudents'])->middleware(['token.permission:students.create', 'throttle:10,1']);
            Route::get('/students/{student}', [StudentController::class, 'show'])->middleware('token.permission:students.view');
            Route::put('/students/{student}', [StudentController::class, 'update'])->middleware('token.permission:students.update');
            Route::delete('/students/{student}', [StudentController::class, 'destroy'])->middleware('token.permission:students.delete');
            Route::get('/teachers', [TeacherController::class, 'index'])->middleware('token.permission:teachers.view');
            Route::post('/teachers', [TeacherController::class, 'store'])->middleware('token.permission:teachers.create');
            Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->middleware('token.permission:teachers.view');
            Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->middleware('token.permission:teachers.update');
            Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->middleware('token.permission:teachers.delete');
            Route::get('/teachers/{teacher}/schedule', [TeacherController::class, 'getSchedule'])->middleware('token.permission:teachers.view');
            Route::get('/teachers/{teacher}/classes', [TeacherController::class, 'getClasses'])->middleware('token.permission:teachers.view');
            Route::get('/attendance', [AttendanceController::class, 'index'])->middleware('token.permission:attendance.view');
            Route::post('/attendance', [AttendanceController::class, 'store'])->middleware('token.permission:attendance.create');
            Route::get('/attendance/student', [AttendanceController::class, 'getByStudent'])->middleware('token.permission:attendance.view');
            Route::get('/attendance/report', [AttendanceController::class, 'getReport'])->middleware('token.permission:attendance.view');
            Route::get('/grades', [GradeController::class, 'index'])->middleware('token.permission:grades.view');
            Route::post('/grades', [GradeController::class, 'store'])->middleware('token.permission:grades.create');
            Route::put('/grades/{grade}', [GradeController::class, 'update'])->middleware('token.permission:grades.update');
            Route::get('/grades/bulletin', [GradeController::class, 'getBulletin'])->middleware('token.permission:grades.view');
            Route::get('/grades/report', [GradeController::class, 'getReport'])->middleware('token.permission:grades.view');
            Route::get('/grades/stats', [GradeController::class, 'getStats'])->middleware('token.permission:grades.view');
            Route::get('/tasks', [SchoolTaskController::class, 'index'])->middleware('token.permission:tasks.view');
            Route::post('/tasks', [SchoolTaskController::class, 'store'])->middleware('token.permission:tasks.create');
            Route::get('/tasks/{task}', [SchoolTaskController::class, 'show'])->middleware('token.permission:tasks.view');
            Route::put('/tasks/{task}', [SchoolTaskController::class, 'update'])->middleware('token.permission:tasks.update');
            Route::delete('/tasks/{task}', [SchoolTaskController::class, 'destroy'])->middleware('token.permission:tasks.delete');
            Route::get('/school-years', [SchoolYearController::class, 'index'])->middleware('token.permission:school-years.view');
            Route::post('/school-years/archive', [SchoolYearController::class, 'archive'])->middleware('token.permission:school-years.archive');
            Route::post('/school-years/restore', [SchoolYearController::class, 'restore'])->middleware('token.permission:school-years.archive');
        });
    });

    Route::middleware('token.school_scope')->group(function () {
        require base_path('app/Modules/Education/Ecoles/SubModules/Maternelle/Routes/api.php');
        require base_path('app/Modules/Education/Ecoles/SubModules/Primaire/Routes/api.php');
        require base_path('app/Modules/Education/Ecoles/SubModules/Secondaire/Routes/api.php');
        require base_path('app/Modules/Education/Ecoles/SubModules/Humanites/Routes/api.php');
    });

    // Universities
    Route::prefix('universities')->group(function () {
        Route::get('/', [UniversityController::class, 'index'])->middleware('token.permission:universities.view');
        Route::post('/', [UniversityController::class, 'store'])->middleware('token.permission:universities.create');
        Route::get('/{university}', [UniversityController::class, 'show'])->middleware('token.permission:universities.view');
        Route::put('/{university}', [UniversityController::class, 'update'])->middleware('token.permission:universities.update');
        Route::delete('/{university}', [UniversityController::class, 'destroy'])->middleware('token.permission:universities.delete');
        Route::prefix('/{university}')->group(function () {
            Route::get('/faculties', [FacultyController::class, 'index'])->middleware('token.permission:faculties.view');
            Route::post('/faculties', [FacultyController::class, 'store'])->middleware('token.permission:faculties.create');
            Route::get('/faculties/{faculty}', [FacultyController::class, 'show'])->middleware('token.permission:faculties.view');
            Route::put('/faculties/{faculty}', [FacultyController::class, 'update'])->middleware('token.permission:faculties.update');
            Route::delete('/faculties/{faculty}', [FacultyController::class, 'destroy'])->middleware('token.permission:faculties.delete');
            Route::get('/departments', [DepartmentController::class, 'index'])->middleware('token.permission:departments.view');
            Route::post('/departments', [DepartmentController::class, 'store'])->middleware('token.permission:departments.create');
            Route::get('/departments/{department}', [DepartmentController::class, 'show'])->middleware('token.permission:departments.view');
            Route::put('/departments/{department}', [DepartmentController::class, 'update'])->middleware('token.permission:departments.update');
            Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->middleware('token.permission:departments.delete');
            Route::get('/courses', [CourseController::class, 'index'])->middleware('token.permission:courses.view');
            Route::post('/courses', [CourseController::class, 'store'])->middleware('token.permission:courses.create');
            Route::get('/courses/{course}', [CourseController::class, 'show'])->middleware('token.permission:courses.view');
            Route::put('/courses/{course}', [CourseController::class, 'update'])->middleware('token.permission:courses.update');
            Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->middleware('token.permission:courses.delete');
            Route::get('/students', [UniversityStudentController::class, 'index'])->middleware('token.permission:students.view');
            Route::post('/students', [UniversityStudentController::class, 'store'])->middleware('token.permission:students.create');
            Route::get('/students/{student}', [UniversityStudentController::class, 'show'])->middleware('token.permission:students.view');
            Route::put('/students/{student}', [UniversityStudentController::class, 'update'])->middleware('token.permission:students.update');
            Route::delete('/students/{student}', [UniversityStudentController::class, 'destroy'])->middleware('token.permission:students.delete');
            Route::get('/grades', [UniversityGradeController::class, 'index'])->middleware('token.permission:grades.view');
            Route::post('/grades', [UniversityGradeController::class, 'store'])->middleware('token.permission:grades.create');
            Route::get('/theses', [ThesisController::class, 'index'])->middleware('token.permission:theses.view');
            Route::post('/theses', [ThesisController::class, 'store'])->middleware('token.permission:theses.create');
            Route::get('/theses/{thesis}', [ThesisController::class, 'show'])->middleware('token.permission:theses.view');
            Route::put('/theses/{thesis}', [ThesisController::class, 'update'])->middleware('token.permission:theses.update');
            Route::delete('/theses/{thesis}', [ThesisController::class, 'destroy'])->middleware('token.permission:theses.delete');
        });
    });

    // Companies
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->middleware('token.permission:companies.view');
        Route::post('/', [CompanyController::class, 'store'])->middleware('token.permission:companies.create');
        Route::get('/{company}', [CompanyController::class, 'show'])->middleware('token.permission:companies.view');
        Route::put('/{company}', [CompanyController::class, 'update'])->middleware('token.permission:companies.update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->middleware('token.permission:companies.delete');
        Route::prefix('/{company}/job-offers')->group(function () {
            Route::get('/', [EntreprisesJobOfferController::class, 'index'])->middleware('token.permission:jobs.view');
            Route::post('/', [EntreprisesJobOfferController::class, 'store'])->middleware('token.permission:jobs.create');
            Route::get('/{jobOffer}', [EntreprisesJobOfferController::class, 'show'])->middleware('token.permission:jobs.view');
            Route::put('/{jobOffer}', [EntreprisesJobOfferController::class, 'update'])->middleware('token.permission:jobs.update');
            Route::delete('/{jobOffer}', [EntreprisesJobOfferController::class, 'destroy'])->middleware('token.permission:jobs.delete');
        });
    });

    // Jobs
    Route::prefix('jobs')->group(function () {
        Route::get('/', [EmploiJobOfferController::class, 'index'])->middleware('token.permission:jobs.view');
        Route::post('/', [EmploiJobOfferController::class, 'store'])->middleware('token.permission:jobs.create');
        Route::get('/{jobOffer}', [EmploiJobOfferController::class, 'show'])->middleware('token.permission:jobs.view');
        Route::put('/{jobOffer}', [EmploiJobOfferController::class, 'update'])->middleware('token.permission:jobs.update');
        Route::delete('/{jobOffer}', [EmploiJobOfferController::class, 'destroy'])->middleware('token.permission:jobs.delete');
        Route::prefix('/{jobOffer}/applications')->group(function () {
            Route::get('/', [JobApplicationController::class, 'index'])->middleware('token.permission:job-applications.view');
            Route::post('/', [JobApplicationController::class, 'store'])->middleware('token.permission:job-applications.create');
            Route::get('/{application}', [JobApplicationController::class, 'show'])->middleware('token.permission:job-applications.view');
            Route::put('/{application}', [JobApplicationController::class, 'update'])->middleware('token.permission:job-applications.update');
            Route::delete('/{application}', [JobApplicationController::class, 'destroy'])->middleware('token.permission:job-applications.delete');
        });
    });

    // Shop
    Route::prefix('shop')->group(function () {
        Route::get('/categories', [ProductCategoryController::class, 'index'])->middleware('token.permission:product-categories.view');
        Route::post('/categories', [ProductCategoryController::class, 'store'])->middleware('token.permission:product-categories.create');
        Route::get('/categories/{category}', [ProductCategoryController::class, 'show'])->middleware('token.permission:product-categories.view');
        Route::put('/categories/{category}', [ProductCategoryController::class, 'update'])->middleware('token.permission:product-categories.update');
        Route::delete('/categories/{category}', [ProductCategoryController::class, 'destroy'])->middleware('token.permission:product-categories.delete');
        Route::get('/products', [ProductController::class, 'index'])->middleware('token.permission:products.view');
        Route::post('/products', [ProductController::class, 'store'])->middleware('token.permission:products.create');
        Route::get('/products/{product}', [ProductController::class, 'show'])->middleware('token.permission:products.view');
        Route::put('/products/{product}', [ProductController::class, 'update'])->middleware('token.permission:products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->middleware('token.permission:products.delete');
        Route::get('/orders', [OrderController::class, 'index'])->middleware('token.permission:orders.view');
        Route::post('/orders', [OrderController::class, 'store'])->middleware('token.permission:orders.create');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware('token.permission:orders.view');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->middleware('token.permission:orders.update');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->middleware('token.permission:orders.delete');
    });

    // Payments
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->middleware('token.permission:payments.view');
        Route::post('/', [PaymentController::class, 'store'])->middleware('token.permission:payments.create');
        Route::get('/{payment}', [PaymentController::class, 'show'])->middleware('token.permission:payments.view');
        Route::put('/{payment}', [PaymentController::class, 'update'])->middleware('token.permission:payments.create');
    });

    // Wallet
    Route::prefix('wallet')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->middleware('token.permission:wallet.view');
        Route::post('/deposit', [WalletController::class, 'deposit'])->middleware('token.permission:wallet.deposit');
        Route::post('/withdraw', [WalletController::class, 'withdraw'])->middleware('token.permission:wallet.withdraw');
        Route::post('/transfer', [WalletController::class, 'transfer'])->middleware('token.permission:wallet.transfer');
        Route::get('/transactions', [WalletController::class, 'transactions'])->middleware('token.permission:wallet.view');
    });

    // Shipments
    Route::prefix('shipments')->group(function () {
        Route::get('/', [ShipmentController::class, 'index'])->middleware('token.permission:shipments.view');
        Route::post('/', [ShipmentController::class, 'store'])->middleware('token.permission:shipments.create');
        Route::get('/{shipment}', [ShipmentController::class, 'show'])->middleware('token.permission:shipments.view');
        Route::put('/{shipment}', [ShipmentController::class, 'update'])->middleware('token.permission:shipments.update');
        Route::delete('/{shipment}', [ShipmentController::class, 'destroy'])->middleware('token.permission:shipments.delete');
    });

    // Conversations
    Route::prefix('conversations')->group(function () {
        Route::get('/', [ConversationController::class, 'index'])->middleware('token.permission:conversations.view');
        Route::post('/', [ConversationController::class, 'store'])->middleware('token.permission:conversations.create');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->middleware('token.permission:conversations.view');
        Route::delete('/{conversation}', [ConversationController::class, 'destroy'])->middleware('token.permission:conversations.delete');
        Route::prefix('/{conversation}/messages')->group(function () {
            Route::get('/', [MessageController::class, 'index'])->middleware('token.permission:messages.view');
            Route::post('/', [MessageController::class, 'store'])->middleware('token.permission:messages.create');
            Route::put('/{message}', [MessageController::class, 'update'])->middleware('token.permission:messages.create');
            Route::delete('/{message}', [MessageController::class, 'destroy'])->middleware('token.permission:messages.delete');
        });
    });

    // Videos
    Route::prefix('videos')->group(function () {
        Route::get('/', [VideoController::class, 'index'])->middleware('token.permission:videos.view');
        Route::post('/', [VideoController::class, 'store'])->middleware('token.permission:videos.create');
        Route::get('/{video}', [VideoController::class, 'show'])->middleware('token.permission:videos.view');
        Route::put('/{video}', [VideoController::class, 'update'])->middleware('token.permission:videos.update');
        Route::delete('/{video}', [VideoController::class, 'destroy'])->middleware('token.permission:videos.delete');
    });

    // Analytics
    Route::prefix('analytics')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->middleware('token.permission:analytics.view');
        Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->middleware('token.permission:analytics.view');
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/me', [SettingController::class, 'mySettings']);
        Route::put('/me', [SettingController::class, 'updateMySettings']);
        Route::get('/', [SettingController::class, 'index'])->middleware('token.permission:settings.view');
        Route::post('/', [SettingController::class, 'update'])->middleware('token.permission:settings.update');
        Route::get('/{key}', [SettingController::class, 'show'])->middleware('token.permission:settings.view');
    });

    // School Sub-modules
    include base_path('app/Modules/Education/Ecoles/SubModules/Maternelle/Routes/api.php');
    include base_path('app/Modules/Education/Ecoles/SubModules/Primaire/Routes/api.php');
    include base_path('app/Modules/Education/Ecoles/SubModules/Secondaire/Routes/api.php');
    include base_path('app/Modules/Education/Ecoles/SubModules/Humanites/Routes/api.php');

    // Notifications
    Route::get('/notifications', function (Request $request) {
        return $request->user()->notifications()->paginate(20);
    });
});
