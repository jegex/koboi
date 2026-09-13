<?php

namespace Jegex\Koboi\Http\Resources;

use Illuminate\Auth\Access\AuthorizationException;
use Jegex\Koboi\Dashboard;
use Jegex\Koboi\Dashboards\Main;
use Jegex\Koboi\Http\Requests\DashboardRequest;
use Jegex\Koboi\Nova;

class DashboardViewResource extends Resource
{
    /**
     * Construct a new Dashboard Resource.
     */
    public function __construct(protected string $name)
    {
        //
    }

    /**
     * Transform the resource into an array.
     *
     * @param  DashboardRequest  $request
     * @return array
     */
    public function toArray($request)
    {
        $dashboard = $this->authorizedDashboardForRequest($request);

        return [
            'label' => $dashboard->label(),
            'cards' => $request->availableCards($this->name),
            'showRefreshButton' => $dashboard->showRefreshButton,
            'isHelpCard' => $dashboard instanceof Main,
        ];
    }

    /**
     * Get authorized dashboard for the request.
     *
     * @throws AuthorizationException
     */
    public function authorizedDashboardForRequest(DashboardRequest $request): Dashboard
    {
        return tap(Nova::dashboardForKey($this->name, $request), static function ($dashboard) {
            abort_if(\is_null($dashboard), 404);
        });
    }
}
