<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use LucasDotVin\Soulbscription\Models\Plan;

class SubscriptionController extends Controller
{
    // Create a subscription for a user
    public function create(User $user)
    {
        $plan = Plan::firstOrCreate([
            'name' => 'Pro Plan',
            'price' => 1999,
            'interval' => 'month',
        ]);

        // ✅ Correct Soulbscription syntax
        $user->subscribeTo($plan);

        return response()->json([
            'message' => 'Subscription created successfully!',
            'user' => $user->name,
            'plan' => $plan->name
        ]);
    }

    // List user subscriptions
    public function list(User $user)
    {
        return response()->json($user->subscriptions ?? []);
    }
}