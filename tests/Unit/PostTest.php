<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_post()
    {
        $post = Post::create([
            'topic' => 'Sample Topic',
        ]);

        $this->assertDatabaseHas('posts', ['topic' => 'Sample Topic']);
    }

    /** @test */
    public function it_can_update_a_post()
    {
        $post = Post::create([
            'topic' => 'Sample Topic',
        ]);

        $post->update(['topic' => 'Updated Topic']);

        $this->assertDatabaseHas('posts', ['topic' => 'Updated Topic']);
    }

    /** @test */
    public function it_can_delete_a_post()
    {
        $post = Post::create([
            'topic' => 'Sample Topic',
        ]);

        $comment = Comment::create([
            'content' => 'Sample Comment',
            'abbreviation' => 'SC',
            'post_id' => $post->id
        ]);

        $post->delete();

        $this->assertDatabaseMissing('posts', ['topic' => 'Sample Topic']);
        $this->assertDatabaseMissing('comments', ['content' => 'Sample Comment']);
    }
}
