<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Services\FakeDataService;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    protected $combinationService;

    public function __construct(FakeDataService $combinationService)
    {
        $this->combinationService = $combinationService;
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
        $combinations = $this->combinationService->generateCombinations($words);

        foreach ($combinations as $combination) {
            // Comment::factory()->create([
            //     'content' => $combination,
            //     'abbreviation' => 'CS'
            // ]);
        }
    }
}
