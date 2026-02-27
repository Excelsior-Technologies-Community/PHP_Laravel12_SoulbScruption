<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use LucasDotVin\Soulbscription\Models\Feature;

class FeatureSeeder extends Seeder
{
    public function run()
    {
        Feature::create([
            'name' => 'deploy_minutes',
            'description' => 'Number of minutes a user can deploy',
            'value' => 120
        ]);

        Feature::create([
            'name' => 'subdomain_access',
            'description' => 'Access to subdomains',
            'value' => true
        ]);
    }
}