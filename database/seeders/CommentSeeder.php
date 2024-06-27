<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Services\FakeDataService;
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
        dd($combinations);

        // foreach ($combinations as $combination) {
        //     // Comment::factory()->create([
        //     //     'content' => $combination,
        //     //     'abbreviation' => 'CS'
        //     // ]);
        // }
    }
}
