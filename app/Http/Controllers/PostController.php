<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Jobs\NotifySubscribers;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        NotifySubscribers::dispatch(Post::create($request->validated()));
        Post::create($request->validated());
        return \response()->noContent(Response::HTTP_CREATED);
    }

}
