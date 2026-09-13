<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Http\Requests\NotificationRequest;
use Jegex\Koboi\Notifications\Notification;

class NotificationDeleteController extends Controller
{
    /**
     * Mark the given notification as read.
     */
    public function __invoke(NotificationRequest $request, string|int $notification): JsonResponse
    {
        $notification = Notification::query()
            ->currentUserFromRequest($request)
            ->findOrFail($notification);

        $notification->update(['read_at' => now()]);
        $notification->delete();

        return response()->json();
    }
}
