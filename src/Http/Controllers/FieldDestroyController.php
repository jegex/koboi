<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Contracts\Deletable;
use Jegex\Koboi\DeleteField;
use Jegex\Koboi\Fields\Field;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Nova;

class FieldDestroyController extends Controller
{
    /**
     * Delete the file at the given field.
     */
    public function __invoke(NovaRequest $request): Response
    {
        $resource = $request->findResourceOrFail();

        $resource->authorizeToUpdate($request);

        /** @var Field&Deletable $field */
        $field = $resource->updateFields($request)
            ->whereInstanceOf(Deletable::class)
            ->findFieldByAttributeOrFail($request->field);

        DeleteField::forRequest(
            $request, $field, $resource->resource
        )->save();

        Nova::usingActionEvent(static function ($actionEvent) use ($request, $resource) {
            $actionEvent->forResourceUpdate(
                Nova::user($request), $resource->resource
            )->save();
        });

        return response()->noContent(200);
    }
}
