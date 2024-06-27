<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_comment()
    {
        $post = Post::create([
            'topic' => 'Sample Topic',
            'abbreviation' => 'ST'
        ]);

        $comment = Comment::create([
            'content' => 'Sample Comment',
            'abbreviation' => 'SC',
            'post_id' => $post->id
        ]);

        $this->assertDatabaseHas('comments', ['content' => 'Sample Comment']);
    }

    /** @test */
    public function it_can_update_a_comment()
    {
        $post = Post::create([
            'topic' => 'Sample Topic',
            'abbreviation' => 'ST'
        ]);

        $comment = Comment::create([
            'content' => 'Sample Comment',
            'abbreviation' => 'SC',
            'post_id' => $post->id
        ]);

        $comment->update(['content' => 'Updated Comment']);

        $this->assertDatabaseHas('comments', ['content' => 'Updated Comment']);
    }

    /** @test */
    public function it_can_delete_a_comment()
    {
        $post = Post::create([
            'topic' => 'Sample Topic',
            'abbreviation' => 'ST'
        ]);

        $comment = Comment::create([
            'content' => 'Sample Comment',
            'abbreviation' => 'SC',
            'post_id' => $post->id
        ]);

        $comment->delete();

        $this->assertDatabaseMissing('comments', ['content' => 'Sample Comment']);
    }
}
