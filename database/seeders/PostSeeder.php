<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 5 posts in the database
        Post::create(['topic' => 'Fishing']);
        Post::create(['topic' => 'Coding']);
        Post::create(['topic' => 'Playing']);
        Post::create(['topic' => 'Traveling']);
        Post::create(['topic' => 'Relaxing']);
    }
}
