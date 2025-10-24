<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $categories = collect([
            'Electronics',
            'Home',
            'Books',
            'Fashion',
            'Beauty',
            'Toys'
            
        ])->map(function ($name) {
            return Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        });


        User::factory(1000)->create();

        foreach (range(1, 2500) as $i) {
            Product::create([
                'name' => $faker->sentence(2),
                'category_id' => $categories->random()->id,
                'price' => $faker->randomFloat(2, 10, 1000),
                'stock' => $faker->numberBetween(0, 100),
                'is_active' => $faker->boolean(90),
            ]);
        }

        $userIds = User::pluck('id');
        $productIds = Product::pluck('id');
        foreach (range(1, 5000) as $i) {
            DB::table('product_users')->insert([
                'user_id' => $userIds->random(),
                'product_id' => $productIds->random(),
                'relation_type' => collect(['purchased', 'wishlisted', 'subscribed'])->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
