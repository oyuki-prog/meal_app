<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategorySeeder::class);
        \App\Models\User::factory(10)->create();
        \App\Models\Post::factory(30)->create();
        \App\Models\Like::factory(100)->create();
    }
}
