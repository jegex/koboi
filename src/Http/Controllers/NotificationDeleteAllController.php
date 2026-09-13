<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\NotificationRequest;
use Jegex\Koboi\Notifications\Notification;

class NotificationDeleteAllController extends Controller
{
    /**
     * Delete all notifications.
     */
    public function __invoke(NotificationRequest $request): JsonResponse
    {
        Notification::query()
            ->currentUserFromRequest($request)
            ->delete();

        return response()->json();
    }
}
