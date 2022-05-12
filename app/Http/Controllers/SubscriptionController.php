<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Jobs\SubscriptionSuccess;
use App\Models\Subscription;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSubscriptionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubscriptionRequest $request)
    {
        SubscriptionSuccess::dispatch(Subscription::create($request->validated()));
        return \response()->noContent(Response::HTTP_CREATED);
    }


}
