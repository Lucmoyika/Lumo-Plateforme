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

// Public auth routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
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
        Route::get('/', [SchoolController::class, 'index']);
        Route::post('/', [SchoolController::class, 'store']);
        Route::get('/{school}', [SchoolController::class, 'show']);
        Route::put('/{school}', [SchoolController::class, 'update']);
        Route::delete('/{school}', [SchoolController::class, 'destroy']);
        Route::prefix('/{school}')->group(function () {
            Route::get('/classes', [SchoolClassController::class, 'index']);
            Route::post('/classes', [SchoolClassController::class, 'store']);
            Route::get('/classes/{class}', [SchoolClassController::class, 'show']);
            Route::put('/classes/{class}', [SchoolClassController::class, 'update']);
            Route::delete('/classes/{class}', [SchoolClassController::class, 'destroy']);
            Route::get('/students', [StudentController::class, 'index']);
            Route::post('/students', [StudentController::class, 'store']);
            Route::get('/students/{student}', [StudentController::class, 'show']);
            Route::put('/students/{student}', [StudentController::class, 'update']);
            Route::delete('/students/{student}', [StudentController::class, 'destroy']);
            Route::get('/teachers', [TeacherController::class, 'index']);
            Route::post('/teachers', [TeacherController::class, 'store']);
            Route::get('/teachers/{teacher}', [TeacherController::class, 'show']);
            Route::put('/teachers/{teacher}', [TeacherController::class, 'update']);
            Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy']);
            Route::get('/attendance', [AttendanceController::class, 'index']);
            Route::post('/attendance', [AttendanceController::class, 'store']);
            Route::get('/grades', [GradeController::class, 'index']);
            Route::post('/grades', [GradeController::class, 'store']);
        });
    });

    // Universities
    Route::prefix('universities')->group(function () {
        Route::get('/', [UniversityController::class, 'index']);
        Route::post('/', [UniversityController::class, 'store']);
        Route::get('/{university}', [UniversityController::class, 'show']);
        Route::put('/{university}', [UniversityController::class, 'update']);
        Route::delete('/{university}', [UniversityController::class, 'destroy']);
        Route::prefix('/{university}')->group(function () {
            Route::get('/faculties', [FacultyController::class, 'index']);
            Route::post('/faculties', [FacultyController::class, 'store']);
            Route::get('/faculties/{faculty}', [FacultyController::class, 'show']);
            Route::put('/faculties/{faculty}', [FacultyController::class, 'update']);
            Route::delete('/faculties/{faculty}', [FacultyController::class, 'destroy']);
            Route::get('/departments', [DepartmentController::class, 'index']);
            Route::post('/departments', [DepartmentController::class, 'store']);
            Route::get('/departments/{department}', [DepartmentController::class, 'show']);
            Route::put('/departments/{department}', [DepartmentController::class, 'update']);
            Route::delete('/departments/{department}', [DepartmentController::class, 'destroy']);
            Route::get('/courses', [CourseController::class, 'index']);
            Route::post('/courses', [CourseController::class, 'store']);
            Route::get('/courses/{course}', [CourseController::class, 'show']);
            Route::put('/courses/{course}', [CourseController::class, 'update']);
            Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
            Route::get('/students', [UniversityStudentController::class, 'index']);
            Route::post('/students', [UniversityStudentController::class, 'store']);
            Route::get('/students/{student}', [UniversityStudentController::class, 'show']);
            Route::put('/students/{student}', [UniversityStudentController::class, 'update']);
            Route::delete('/students/{student}', [UniversityStudentController::class, 'destroy']);
            Route::get('/grades', [UniversityGradeController::class, 'index']);
            Route::post('/grades', [UniversityGradeController::class, 'store']);
            Route::get('/theses', [ThesisController::class, 'index']);
            Route::post('/theses', [ThesisController::class, 'store']);
            Route::get('/theses/{thesis}', [ThesisController::class, 'show']);
            Route::put('/theses/{thesis}', [ThesisController::class, 'update']);
            Route::delete('/theses/{thesis}', [ThesisController::class, 'destroy']);
        });
    });

    // Companies
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index']);
        Route::post('/', [CompanyController::class, 'store']);
        Route::get('/{company}', [CompanyController::class, 'show']);
        Route::put('/{company}', [CompanyController::class, 'update']);
        Route::delete('/{company}', [CompanyController::class, 'destroy']);
        Route::prefix('/{company}/job-offers')->group(function () {
            Route::get('/', [EntreprisesJobOfferController::class, 'index']);
            Route::post('/', [EntreprisesJobOfferController::class, 'store']);
            Route::get('/{jobOffer}', [EntreprisesJobOfferController::class, 'show']);
            Route::put('/{jobOffer}', [EntreprisesJobOfferController::class, 'update']);
            Route::delete('/{jobOffer}', [EntreprisesJobOfferController::class, 'destroy']);
        });
    });

    // Jobs
    Route::prefix('jobs')->group(function () {
        Route::get('/', [EmploiJobOfferController::class, 'index']);
        Route::post('/', [EmploiJobOfferController::class, 'store']);
        Route::get('/{jobOffer}', [EmploiJobOfferController::class, 'show']);
        Route::put('/{jobOffer}', [EmploiJobOfferController::class, 'update']);
        Route::delete('/{jobOffer}', [EmploiJobOfferController::class, 'destroy']);
        Route::prefix('/{jobOffer}/applications')->group(function () {
            Route::get('/', [JobApplicationController::class, 'index']);
            Route::post('/', [JobApplicationController::class, 'store']);
            Route::get('/{application}', [JobApplicationController::class, 'show']);
            Route::put('/{application}', [JobApplicationController::class, 'update']);
            Route::delete('/{application}', [JobApplicationController::class, 'destroy']);
        });
    });

    // Shop
    Route::prefix('shop')->group(function () {
        Route::get('/categories', [ProductCategoryController::class, 'index']);
        Route::post('/categories', [ProductCategoryController::class, 'store']);
        Route::get('/categories/{category}', [ProductCategoryController::class, 'show']);
        Route::put('/categories/{category}', [ProductCategoryController::class, 'update']);
        Route::delete('/categories/{category}', [ProductCategoryController::class, 'destroy']);
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products/{product}', [ProductController::class, 'show']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::put('/orders/{order}', [OrderController::class, 'update']);
        Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
    });

    // Payments
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index']);
        Route::post('/', [PaymentController::class, 'store']);
        Route::get('/{payment}', [PaymentController::class, 'show']);
        Route::put('/{payment}', [PaymentController::class, 'update']);
    });

    // Wallet
    Route::prefix('wallet')->group(function () {
        Route::get('/', [WalletController::class, 'index']);
        Route::post('/deposit', [WalletController::class, 'deposit']);
        Route::post('/withdraw', [WalletController::class, 'withdraw']);
        Route::post('/transfer', [WalletController::class, 'transfer']);
        Route::get('/transactions', [WalletController::class, 'transactions']);
    });

    // Shipments
    Route::prefix('shipments')->group(function () {
        Route::get('/', [ShipmentController::class, 'index']);
        Route::post('/', [ShipmentController::class, 'store']);
        Route::get('/{shipment}', [ShipmentController::class, 'show']);
        Route::put('/{shipment}', [ShipmentController::class, 'update']);
        Route::delete('/{shipment}', [ShipmentController::class, 'destroy']);
    });

    // Conversations
    Route::prefix('conversations')->group(function () {
        Route::get('/', [ConversationController::class, 'index']);
        Route::post('/', [ConversationController::class, 'store']);
        Route::get('/{conversation}', [ConversationController::class, 'show']);
        Route::delete('/{conversation}', [ConversationController::class, 'destroy']);
        Route::prefix('/{conversation}/messages')->group(function () {
            Route::get('/', [MessageController::class, 'index']);
            Route::post('/', [MessageController::class, 'store']);
            Route::put('/{message}', [MessageController::class, 'update']);
            Route::delete('/{message}', [MessageController::class, 'destroy']);
        });
    });

    // Videos
    Route::prefix('videos')->group(function () {
        Route::get('/', [VideoController::class, 'index']);
        Route::post('/', [VideoController::class, 'store']);
        Route::get('/{video}', [VideoController::class, 'show']);
        Route::put('/{video}', [VideoController::class, 'update']);
        Route::delete('/{video}', [VideoController::class, 'destroy']);
    });

    // Analytics
    Route::prefix('analytics')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index']);
        Route::get('/dashboard', [AnalyticsController::class, 'dashboard']);
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::post('/', [SettingController::class, 'update']);
        Route::get('/{key}', [SettingController::class, 'show']);
    });

    // Notifications
    Route::get('/notifications', function (Request $request) {
        return $request->user()->notifications()->paginate(20);
    });
});
