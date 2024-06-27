<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $filterable = [];
    protected $availableRelations = [];

    protected function filterAndRespond($query, $request)
    {
        $limit = $request->get('limit', 10);
        $page = $request->get('page', 1);
        $direction = $request->get('direction', 'asc');
        $sort = $request->get('sort', 'id');

        foreach ($request->all() as $key => $value) {
            if (in_array($key, $this->filterable)) {
                if ($key == 'id') {
                    $query->where($key, $value);
                } else {
                    $query->where($key, 'like', '%' . $value . '%');
                }
            }
        }

        if ($request->has('with') && in_array($request->with, $this->availableRelations)) {
            $query->with($request->with);
        } else {
            return $this->errorResponse('Invalid relation', 400);
        }

        if (in_array($sort, $this->filterable)) {
            $query->orderBy($sort, $direction);
        } else {
            return $this->errorResponse('Invalid sort', 400);
        }

        $total = $query->count();
        $result = $query->skip(($page - 1) * $limit)->take($limit)->get();

        return response()->json(['result' => $result, 'count' => $total]);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message], $code);
    }
}
