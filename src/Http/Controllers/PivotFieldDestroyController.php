<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jegex\Koboi\DeleteField;
use Jegex\Koboi\Http\Requests\PivotFieldDestroyRequest;
use Jegex\Koboi\Nova;

class PivotFieldDestroyController extends Controller
{
    /**
     * Delete the file at the given field.
     */
    public function __invoke(PivotFieldDestroyRequest $request): Response
    {
        $request->authorizeForAttachment();

        DeleteField::forRequest(
            $request, $request->findFieldOrFail(),
            $pivot = $request->findPivotModel()
        )->save();

        Nova::usingActionEvent(static function ($actionEvent) use ($request, $pivot) {
            $actionEvent->forAttachedResourceUpdate(
                $request, $request->findModelOrFail(), $pivot
            )->save();
        });

        return response()->noContent(200);
    }
}
