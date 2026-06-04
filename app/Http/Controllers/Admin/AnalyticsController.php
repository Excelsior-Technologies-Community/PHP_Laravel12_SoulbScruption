<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use LucasDotVin\Soulbscription\Models\Subscription;
use App\Models\Invoice;
use App\Models\Coupon;  
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $revenue = DB::table('subscriptions')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->whereNull('subscriptions.canceled_at')
            ->sum('plans.price');

        $activeUsers = Subscription::whereNull('canceled_at')->count();
        $subscriptions = Subscription::with(['plan', 'subscriber'])->whereNull('canceled_at')->get();
        
    
        $invoices = Invoice::latest()->get();
        $coupons = Coupon::all();

        return view('admin.analytics', compact('revenue', 'activeUsers', 'subscriptions', 'invoices', 'coupons'));
    }
}