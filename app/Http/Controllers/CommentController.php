<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Services\FakeDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {
        $request->merge([
            'content' => strtolower($request->content),
            'abbreviation' => strtolower($request->abbreviation),
        ]);

        $validator = Validator::make($request->all(), [
            'abbreviation' => 'required|regex:/^\S*$/|unique:comments,abbreviation',
            'content' => 'required|unique:comments,content',
            'post_id' => 'required|exists:posts,id'
        ]);

        $validator->after(function ($validator) use ($request) {
            $content = $request->input('content');
            $combinations = $this->fakeDataService->generateWordsCombinations($content);

            foreach ($combinations as $combination) {
                if (Comment::where('content', $combination)->exists()) {
                    $validator->errors()->add('content', 'The content is a duplicate considering word combinations.');
                    break;
                }
            }
        });

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

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
