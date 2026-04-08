<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('welcome'); });
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
Route::get('/schools', function () { return view('schools.index'); })->name('schools');
Route::get('/universities', function () { return view('universities.index'); })->name('universities');
Route::get('/jobs', function () { return view('jobs.index'); })->name('jobs');
Route::get('/companies', function () { return view('companies.index'); })->name('companies');
Route::get('/shop', function () { return view('shop.index'); })->name('shop');
Route::get('/chat', function () { return view('chat.index'); })->name('chat');
Route::get('/wallet', function () { return view('wallet.index'); })->name('wallet');
Route::get('/videos', function () { return view('videos.index'); })->name('videos');
Route::get('/profile', function () { return view('profile.index'); })->name('profile');
Route::get('/settings', function () { return view('settings.index'); })->name('settings');
Route::get('/admin/audit-logs', function () { return view('admin.audit-logs'); })->name('admin.audit-logs');
Route::fallback(function () { abort(404); });
