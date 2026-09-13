<?php

namespace Jegex\Koboi;

use Illuminate\Http\Request;

interface HasMenu
{
    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @return mixed
     */
    public function menu(Request $request);
}
