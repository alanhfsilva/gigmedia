<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_does_not_allow_white_spaces_in_abbreviation()
    {
        $post = Post::factory()->create();

        $response = $this->postJson('/api/comments', [
            'content' => 'unique content',
            'abbreviation' => 'white space',
            'post_id' => $post->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['abbreviation']);
    }

    /** @test */
    public function it_detects_duplicate_content_combinations()
    {
        $post = Post::factory()->create();

        Comment::create([
            'content' => 'cool strange',
            'abbreviation' => 'cs',
            'post_id' => $post->id
        ]);

        $response = $this->postJson('/api/comments', [
            'content' => 'strange cool',
            'abbreviation' => 'sc',
            'post_id' => $post->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /** @test */
    public function it_converts_content_and_abbreviation_to_lowercase()
    {
        $post = Post::factory()->create();

        $response = $this->postJson('/api/comments', [
            'content' => 'UPPERCASE CONTENT',
            'abbreviation' => 'UC',
            'post_id' => $post->id
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['content' => 'uppercase content', 'abbreviation' => 'uc']);
    }

    /** @test */
    public function it_validates_abbreviation_matches_content_initials()
    {
        $post = Post::factory()->create();

        $response = $this->postJson('/api/comments', [
            'content' => 'cool strange',
            'abbreviation' => 'cs',
            'post_id' => $post->id
        ]);

        $response->assertStatus(201);

        $response = $this->postJson('/api/comments', [
            'content' => 'cool strange',
            'abbreviation' => 'c s',
            'post_id' => $post->id
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['abbreviation']);
    }
}
