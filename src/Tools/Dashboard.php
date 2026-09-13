<?php

namespace Jegex\Koboi\Tools;

use Illuminate\Http\Request;
use Jegex\Koboi\Menu\MenuSection;
use Jegex\Koboi\Nova;
use Jegex\Koboi\Tool;

class Dashboard extends Tool
{
    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @return mixed
     */
    public function menu(Request $request)
    {
        $dashboards = collect(Nova::availableDashboards($request));

        if ($dashboards->count() > 1) {
            return MenuSection::make(
                Nova::__('Dashboards'), $dashboards->map(fn ($dashboard) => $dashboard->menu($request))
            )->collapsable()
                ->icon('squares-2-x-2');
        }

        if ($dashboards->count() == 1) {
            return MenuSection::make($dashboards->first()->label(), $dashboards)
                ->path("/dashboards/{$dashboards->first()->uriKey()}")
                ->icon('squares-2-x-2');
        }
    }
}
