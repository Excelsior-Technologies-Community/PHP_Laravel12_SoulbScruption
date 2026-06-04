<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use LucasDotVin\Soulbscription\Models\Plan;
use LucasDotVin\Soulbscription\Models\Feature;

class SubscriptionController extends Controller
{
    public function create(User $user)
    {
        $plan = Plan::firstOrCreate([
            'name' => 'Pro Plan',
            'price' => 1999,
            'interval' => 'month',
        ]);

        $feature = Feature::firstOrCreate([
            'name' => 'deploy_minutes'
        ], [
            'description' => 'Number of minutes a user can deploy',
            'value' => 120
        ]);

        $plan->features()->syncWithoutDetaching([
            $feature->id => ['value' => 120]
        ]);

        $user->subscribeTo($plan);

        return response()->json([
            'message' => 'Subscription created successfully!',
            'user' => $user->name,
            'plan' => $plan->name
        ]);
    }

    public function list(User $user)
    {
        $subscription = $user->subscription;

        return response()->json([
            'has_subscription' => (bool) $subscription,
            'plan' => $subscription?->plan?->name
        ]);
    }

    public function checkFeature(User $user)
    {
        $featureName = 'deploy_minutes';

        $feature = $user->checkFeature($featureName);

        if ($feature) {
            return response()->json([
                'status' => 'allowed',
                'feature' => $featureName,
                'value' => $feature->pivot->value ?? $feature->value,
                'message' => 'User has access to this feature'
            ]);
        }

        return response()->json([
            'status' => 'denied',
            'message' => 'Feature not available in this plan'
        ]);
    }

    public function cancel(User $user)
    {
        $subscription = $user->subscription;

        if (!$subscription) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active subscription found'
            ]);
        }

        $subscription->cancel();

        return response()->json([
            'status' => 'success',
            'message' => 'Subscription cancelled successfully'
        ]);
    }
}