<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Ecoles\Models\Student;
use App\Modules\Education\Ecoles\Models\Teacher;

Route::get('/', function () { return view('welcome'); });
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.register'); })->name('register');

Route::middleware('token.auth')->group(function () {
	Route::get('/dashboard', function (Request $request) {
		$user = $request->user();

		if ($user->hasAnyRole(['super_admin', 'admin'])) {
			return view('dashboard');
		}

		if ($user->hasRole('school_admin') || $user->school()->exists()) {
			return redirect()->route('schools.my');
		}

		$teacherSchoolId = Teacher::query()
			->where('user_id', $user->id)
			->value('school_id');

		if ($teacherSchoolId) {
			return redirect()->route('schools.my');
		}

		$studentSchoolId = Student::query()
			->where('user_id', $user->id)
			->value('school_id');

		if ($studentSchoolId) {
			return redirect()->route('schools.my');
		}

		if ($user->hasAnyRole(['school_staff', 'assistant', 'teacher', 'substitute_teacher']) && $user->can('schools.view')) {
			return redirect()->route('schools');
		}

		if ($user->can('schools.view')) {
			return redirect()->route('schools');
		}

		if ($user->can('universities.view')) {
			return redirect()->route('universities');
		}

		if ($user->can('companies.view')) {
			return redirect()->route('companies');
		}

		if ($user->can('jobs.view')) {
			return redirect()->route('jobs');
		}

		if ($user->can('products.view') || $user->can('orders.view')) {
			return redirect()->route('shop');
		}

		if ($user->can('wallet.view')) {
			return redirect()->route('wallet');
		}

		if ($user->can('conversations.view')) {
			return redirect()->route('chat');
		}

		return view('dashboard');
	})->name('dashboard');
	Route::get('/profile', function () { return view('profile.index'); })->name('profile');
	Route::get('/settings', function () { return view('settings.index'); })->name('settings');
	Route::get('/notifications', function () { return view('notifications.index'); })->name('notifications');

	Route::get('/schools/my', function (Request $request) {
		$user = $request->user();

		abort_unless($user, 403, 'User does not have the right permissions.');

		if ($user->hasRole('school_admin') || $user->school()->exists()) {
			return view('schools.my');
		}

		$teacherSchoolId = Teacher::query()
			->where('user_id', $user->id)
			->value('school_id');

		if ($teacherSchoolId) {
			return redirect()->route('schools.show', ['school' => $teacherSchoolId]);
		}

		$studentSchoolId = Student::query()
			->where('user_id', $user->id)
			->value('school_id');

		if ($studentSchoolId) {
			return redirect()->route('schools.show', ['school' => $studentSchoolId]);
		}

		abort(403, 'User does not have the right permissions.');

	})->name('schools.my');

	Route::middleware('permission:schools.view')->group(function () {
		Route::get('/schools', function () { return view('schools.index'); })->name('schools');
	});

	Route::middleware('token.school_scope')->group(function () {
		Route::get('/schools/{school}', function (Request $request, int $school) {
			$schoolModel = School::query()->select(['id', 'name', 'level_types', 'city', 'address', 'description', 'status', 'email', 'phone', 'director_id'])->find($school);

			abort_unless($schoolModel, 404);

			return view('schools.show', [
				'schoolId' => $schoolModel->id,
				'school' => $schoolModel,
			]);
		})->whereNumber('school')->name('schools.show');
	});

	Route::middleware('permission:schools.create')->group(function () {
		Route::get('/schools/create', function () { return view('schools.create'); })->name('schools.create');
	});

	Route::middleware('permission:universities.view')->group(function () {
		Route::get('/universities', function () { return view('universities.index'); })->name('universities');
		Route::get('/universities/1', function () { return view('universities.show'); })->name('universities.show');
	});

	Route::middleware('permission:jobs.view')->group(function () {
		Route::get('/jobs', function () { return view('jobs.index'); })->name('jobs');
		Route::get('/jobs/1', function () { return view('jobs.show'); })->name('jobs.show');
	});

	Route::middleware('permission:companies.view')->group(function () {
		Route::get('/companies', function () { return view('companies.index'); })->name('companies');
		Route::get('/companies/1', function () { return view('companies.show'); })->name('companies.show');
	});

	Route::middleware('permission:products.view|orders.view')->group(function () {
		Route::get('/shop', function () { return view('shop.index'); })->name('shop');
		Route::get('/shop/cart', function () { return view('shop.cart'); })->name('shop.cart');
		Route::get('/shop/checkout', function () { return view('shop.checkout'); })->name('shop.checkout');
		Route::get('/shop/orders', function () { return view('shop.orders'); })->name('shop.orders');
		Route::get('/shop/products/1', function () { return view('shop.product'); })->name('shop.product');
	});

	Route::middleware('permission:conversations.view')->group(function () {
		Route::get('/chat', function () { return view('chat.index'); })->name('chat');
	});

	Route::middleware('permission:wallet.view')->group(function () {
		Route::get('/wallet', function () { return view('wallet.index'); })->name('wallet');
	});

	Route::middleware('permission:videos.view')->group(function () {
		Route::get('/videos', function () { return view('videos.index'); })->name('videos');
		Route::get('/videos/1', function () { return view('videos.show'); })->name('videos.show');
	});

	Route::middleware('permission:shipments.view')->group(function () {
		Route::get('/logistics', function () { return view('logistics.index'); })->name('logistics');
	});

	Route::middleware('permission:analytics.view')->group(function () {
		Route::get('/analytics', function () { return view('analytics.index'); })->name('analytics');
	});

	Route::middleware('permission:users.view')->group(function () {
		Route::get('/admin', function () { return view('admin.index'); })->name('admin');
		Route::get('/admin/users', function () { return view('admin.users'); })->name('admin.users');
	});

	Route::middleware('permission:audit-logs.view')->group(function () {
		Route::get('/admin/audit-logs', function () { return view('admin.audit-logs'); })->name('admin.audit-logs');
	});
});

Route::fallback(function () { abort(404); });
