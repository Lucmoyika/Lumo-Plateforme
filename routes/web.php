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
Route::get('/logistics', function () { return view('logistics.index'); })->name('logistics');
Route::get('/analytics', function () { return view('analytics.index'); })->name('analytics');
Route::get('/admin', function () { return view('admin.index'); })->name('admin');
Route::get('/admin/users', function () { return view('admin.users'); })->name('admin.users');
Route::get('/jobs/1', function () { return view('jobs.show'); })->name('jobs.show');
Route::get('/companies/1', function () { return view('companies.show'); })->name('companies.show');
Route::get('/schools/1', function () { return view('schools.show'); })->name('schools.show');
Route::get('/universities/1', function () { return view('universities.show'); })->name('universities.show');
Route::get('/shop/cart', function () { return view('shop.cart'); })->name('shop.cart');
Route::get('/shop/checkout', function () { return view('shop.checkout'); })->name('shop.checkout');
Route::get('/shop/orders', function () { return view('shop.orders'); })->name('shop.orders');
Route::get('/shop/products/1', function () { return view('shop.product'); })->name('shop.product');
Route::get('/videos/1', function () { return view('videos.show'); })->name('videos.show');
Route::get('/notifications', function () { return view('notifications.index'); })->name('notifications');
Route::fallback(function () { abort(404); });
