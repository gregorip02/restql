<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $this->call(AuthorsTableSeeder::class);
            $this->call(ArticlesTableSeeder::class);
            $this->call(CommentsTableSeeder::class);
        });
    }
}
