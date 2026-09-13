<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\NotificationRequest;

class NotificationIndexController extends Controller
{
    /**
     * Return the details for the Dashboard.
     */
    public function __invoke(NotificationRequest $request): JsonResponse
    {
        return response()->json([
            'notifications' => $request->notifications(),
            'unread' => $request->unreadCount(),
        ]);
    }
}
