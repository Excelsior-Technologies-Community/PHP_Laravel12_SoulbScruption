# PHP_Laravel12_SoulbScruption

## Project Description: 

PHP_Laravel12_SoulbScruption is a Laravel 12 project that demonstrates how to implement a subscription system using the Soulbscription package by lucasdotvin. This project allows users to subscribe to different plans and access features tied to those plans. It is designed as a SaaS-style subscription management system with database-driven features, plans, and subscriptions.


## Key Features

1. User Subscription Management – Subscribe users to plans and assign features.
2. Dynamic Features – Create features (e.g., deploy minutes, subdomain access) linked to plans.
3. Plan Management – Price, billing interval, and soft delete support.
4. Seeder & Factory Support – Generate sample users and features for testing.
5. Routes & JSON Responses – Create subscriptions and list them with JSON output.
6. Extendable – Ready for payment gateways and dashboards.


## Key Technologies

- PHP 8.x
- Laravel 12
- MySQL
- Soulbscription package
- Composer & Artisan
- Eloquent ORM
- Seeder & Factory
- JSON responses

---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_SoulbScruption "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_SoulbScruption

```

#### Explanation:

Installs a fresh Laravel 12 project and navigates into the project folder.




## STEP 2: Database Setup (Optional)

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_SoulbScruption
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_SoulbScruption

```

#### Explanation:

Configures Laravel to connect with your MySQL database.

This database stores all users, plans, features, and subscriptions.



## STEP 3: Install Soulbscription Package

### Run:

```
composer require lucasdotvin/laravel-soulbscription

```

### Publish migrations & configs:

```
php artisan vendor:publish --tag="soulbscription-migrations"

php artisan migrate

```

#### Explanation:

Installs the Soulbscription package, publishes migrations, and creates the necessary tables in the database.





## STEP 4: Check the Migration

### Go to:

```
database/migrations/

```

### Look for a migration like:

```
202*_create_features_table.php

```

### Full migration like this:

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('value')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('features');
    }
};

```


### Find the Soulbscription migration for plans, probably:

```
database/migrations/202*_create_plans_table.php

```

### We need to add price and interval columns:

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price')->default(0); // Price in cents
            $table->string('interval')->default('month'); // month, year, etc.
            $table->timestamps();
            $table->softDeletes(); // For deleted_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
};

```


### Then Run:

```
php artisan migrate:refresh

```

#### Explanation:

Check features and plans migrations. Ensure plans has price and interval columns.





## STEP 5: User Model Setup

### Edit app/Models/User.php:

```
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LucasDotVin\Soulbscription\Models\Concerns\HasSubscriptions; // THIS IS REQUIRED

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasSubscriptions;
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}

```

#### Explanation:

Adds the HasSubscriptions trait so each user can have subscriptions and access features.






## STEP 6: Seeder - Create Features

### Create seeder:

```
php artisan make:seeder FeatureSeeder

```

### Edit database/seeders/FeatureSeeder.php:

```
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

```


### Run seeder:

```
php artisan db:seed --class=FeatureSeeder

```

#### Explanation:

Creates sample features that can be assigned to subscription plans.






## STEP 7: Subscription Controller

### Create controller:

```
php artisan make:controller SubscriptionController

```

### Edit app/Http/Controllers/SubscriptionController.php:

```
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

```

#### Explanation:

Controller handles creating a subscription for a user and listing all their subscriptions.





## STEP 8: Routes

### Edit routes/web.php:

```
use App\Http\Controllers\SubscriptionController;

Route::get('/subscribe/{user}', [SubscriptionController::class, 'create']);
Route::get('/subscriptions/{user}', [SubscriptionController::class, 'list']);

```


#### Explanation:

Defines URLs to create a subscription and view a user's subscriptions.





## STEP 9: Create a User Factory

### Run this in terminal:

```
php artisan make:factory UserFactory --model=User

```

### This will create:

```
database/factories/UserFactory.php

```

### Then edit it like this:

```
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // default password
            'remember_token' => Str::random(10),
        ];
    }
}

```

#### Explanation:

Generates fake users for testing subscriptions quickly.





## STEP 10: Test User

### Create a user via tinker:

```
php artisan tinker
\App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('test@1234')
]);


exit;

```

#### Explanation:

Creates a test user to assign subscriptions.




## STEP 11: Test Subscription

### Open browser:

```
http://127.0.0.1:8000/subscribe/1

```

#### Expected Output:


<img width="1919" height="749" alt="Screenshot 2026-02-27 145105" src="https://github.com/user-attachments/assets/47f769e5-51c4-44c5-9c55-fda4300112ed" />


### Open Postman:

1. Method: Get

2. Fetch: 

```
http://127.0.0.1:8000/subscribe/1

```

#### Expected Output:


<img width="1444" height="827" alt="Screenshot 2026-02-27 152303" src="https://github.com/user-attachments/assets/64a7b707-706b-4505-90b5-c7ca96b90d5d" />



#### Explanation:

Visits the routes to create a subscription and list the user's subscriptions in JSON format.



--- 

# Project Folder Structure:

```

PHP_Laravel12_SoulbScruption/
├─ app/
│  ├─ Http/
│  │  ├─ Controllers/
│  │  │  └─ SubscriptionController.php
│  ├─ Models/
│  │  └─ User.php
├─ database/
│  ├─ seeders/
│  │  └─ FeatureSeeder.php
├─ routes/
│  └─ web.php
├─ vendor/ (after composer install)
├─ composer.json
└─ .env

```
