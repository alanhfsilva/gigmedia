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
        }

        $query->orderBy($sort, $direction);

        $total = $query->count();
        $result = $query->skip(($page - 1) * $limit)->take($limit)->get();

        return response()->json(['result' => $result, 'count' => $total]);
    }
}
