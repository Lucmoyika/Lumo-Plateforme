<?php

namespace App\Modules\Analytics\Services;

use App\Models\User;
use App\Modules\Ecommerce\Models\Order;
use App\Modules\Emploi\Models\JobApplication;
use App\Modules\Emploi\Models\JobOffer;
use App\Modules\Education\Ecoles\Models\School;
use App\Modules\Education\Universites\Models\University;

class AnalyticsService
{
    public function overview(): array
    {
        return [
            'users'        => User::count(),
            'active_users' => User::where('status','active')->count(),
            'schools'      => School::count(),
            'universities' => University::count(),
            'job_offers'   => JobOffer::count(),
            'orders'       => Order::count(),
        ];
    }

    public function educationStats(): array
    {
        return [
            'schools'      => ['total' => School::count(), 'active' => School::where('status','active')->count()],
            'universities' => ['total' => University::count()],
            'students'     => \App\Modules\Education\Ecoles\Models\Student::count(),
            'teachers'     => \App\Modules\Education\Ecoles\Models\Teacher::count(),
        ];
    }

    public function employmentStats(): array
    {
        return [
            'job_offers'       => JobOffer::count(),
            'active_offers'    => JobOffer::where('status','active')->count(),
            'applications'     => JobApplication::count(),
            'accepted'         => JobApplication::where('status','accepted')->count(),
        ];
    }

    public function ecommerceStats(): array
    {
        return [
            'orders'         => Order::count(),
            'revenue'        => Order::where('status','delivered')->sum('total_amount'),
            'pending_orders' => Order::where('status','pending')->count(),
            'products'       => \App\Modules\Ecommerce\Models\Product::count(),
        ];
    }

    public function userStats(): array
    {
        return [
            'total'        => User::count(),
            'this_month'   => User::whereMonth('created_at', now()->month)->count(),
            'by_role'      => User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total','role'),
            'premium'      => User::where('is_premium', true)->count(),
        ];
    }
}
