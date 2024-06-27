<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    protected $filterable = [
        'id',
        'content',
        'abbreviation',
        'post_id',
        'created_at',
        'updated_at',
    ];

    protected $availableRelations = ['post'];

    public function index(Request $request)
    {
        $query = Comment::query();

        return $this->filterAndRespond($query, $request);
    }

    public function store(Request $request)
    {
        $request->validate([
            'abbreviation' => 'required|unique:comments,abbreviation',
            'content' => 'required|unique:comments,content',
            'post_id' => 'required|exists:posts,id'
        ]);

        $comment = Comment::create($request->all());

        return response()->json($comment, 201);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);

        if ($comment) {
            $comment->delete();
            return response()->json(['result' => true]);
        }

        return $this->errorResponse('Comment not found', 404);
    }
}
