<?php

namespace Tests\Feature;

use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_a_list_of_posts()
    {
        Post::factory()->count(20)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200);
        $response->assertJsonStructure([
                'result' => [
                    '*' => ['id', 'topic', 'created_at', 'updated_at']
                ],
                'count'
            ]);
        $response->assertJsonCount(10, 'result');
        $response->assertJsonCount(20, 'count');
    }

    /** @test */
    public function it_can_delete_a_post()
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson(['result' => true]);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
