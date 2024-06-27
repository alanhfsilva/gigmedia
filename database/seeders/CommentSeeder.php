<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Services\FakeDataService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    protected $fakeDataService;

    public function __construct(FakeDataService $fakeDataService)
    {
        $this->fakeDataService = $fakeDataService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate fake comments based on combinations
        $randomWords = "Cool,Strange,Funny,Laughing,Nice,Awesome,Great,Horrible,Beautiful,PHP,Vegeta,Italy,Joost";
        $words = explode(',', $randomWords);
        $combinations = $this->fakeDataService->generateCombinations($words);

        // Get the current available posts
        $posts = Post::all();

        $data = [];
        $chunkSize = 200;

        foreach ($combinations as $combination) {
            $data[] = [
                'content' => $combination,
                'abbreviation' => $this->fakeDataService->generateAbbreviation($combination),
                'post_id' => $posts->random()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Split it into chunks to avoid multiple connections with the database. It will bring better performance (ms compared to s).
        $chunks = array_chunk($data, $chunkSize);

        foreach ($chunks as $chunk) {
            Comment::insert($chunk);
        }
    }
}
