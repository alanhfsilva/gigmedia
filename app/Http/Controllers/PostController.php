<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    protected $filterable = [
        'id',
        'topic',
        'abbreviation',
        'created_at',
        'updated_at',
    ];

    public function index(Request $request)
    {
        $query = Post::query();

        if ($request->has('comment')) {
            $query->whereHas('comments', function($q) use ($request) {
                $q->where('content', 'like', '%' . $request->comment . '%');
            });
        }

        return $this->filterAndRespond($query, $request);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if ($post) {
            $comments = $post->comments;
            $post->delete();

            return response()->json([
                'result' => true,
                'deleted_comments' => $comments->count(),
            ]);
        }

        return $this->errorResponse('Post not found', 404);
    }
}
