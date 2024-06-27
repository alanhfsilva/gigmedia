<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Services\FakeDataService;
use Illuminate\Http\Request;

class CommentController extends BaseController
{
    protected $fakeDataService;

    protected $filterable = [
        'id',
        'content',
        'abbreviation',
        'post_id',
        'created_at',
        'updated_at',
    ];

    protected $availableRelations = ['post'];

    public function __construct(FakeDataService $fakeDataService)
    {
        $this->fakeDataService = $fakeDataService;
    }

    public function index(Request $request)
    {
        $query = Comment::query();

        return $this->filterAndRespond($query, $request);
    }

    public function store(StoreCommentRequest $request)
    {
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
