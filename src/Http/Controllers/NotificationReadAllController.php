<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\NotificationRequest;
use Jegex\Koboi\Notifications\Notification;

class NotificationReadAllController extends Controller
{
    /**
     * Mark the given notification as read.
     */
    public function __invoke(NotificationRequest $request): JsonResponse
    {
        $request->markAsRead();

        return response()->json();
    }
}
