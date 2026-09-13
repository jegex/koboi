<?php

namespace Jegex\Koboi\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Jegex\Koboi\Contracts\Previewable;
use Jegex\Koboi\Http\Requests\ResourceCreateOrAttachRequest;
use Jegex\Koboi\Http\Requests\ResourceUpdateOrUpdateAttachedRequest;
use Jegex\Koboi\Http\Resources\CreateViewResource;
use Jegex\Koboi\Http\Resources\CreationPivotFieldResource;
use Jegex\Koboi\Http\Resources\UpdatePivotFieldResource;
use Jegex\Koboi\Http\Resources\UpdateViewResource;

class FieldPreviewController extends Controller
{
    /**
     * Preview the field for "create" request.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function create(ResourceCreateOrAttachRequest $request): JsonResponse
    {
        $request->validate(['value' => ['nullable', 'string']]);

        /** @var \Jegex\Koboi\Fields\Field&\Jegex\Koboi\Contracts\Previewable $field */
        $field = CreateViewResource::make()
            ->newResourceWith($request)
            ->creationFields($request)
            ->whereInstanceOf(Previewable::class)
            ->findFieldByAttributeOrFail($request->field);

        return response()->json([
            'preview' => $field->previewFor($request->value),
        ]);
    }

    /**
     * Preview the field for "attach" request.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function createPivot(ResourceCreateOrAttachRequest $request): JsonResponse
    {
        $request->validate(['value' => ['nullable', 'string']]);

        /** @var \Jegex\Koboi\Fields\Field&\Jegex\Koboi\Contracts\Previewable $field */
        $field = CreationPivotFieldResource::make()
            ->newResourceWith($request)
            ->creationPivotFields($request, $request->relatedResource)
            ->whereInstanceOf(Previewable::class)
            ->findFieldByAttributeOrFail($request->field);

        return response()->json([
            'preview' => $field->previewFor($request->value),
        ]);
    }

    /**
     * Preview the field for "update" request.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function update(ResourceUpdateOrUpdateAttachedRequest $request): JsonResponse
    {
        $request->validate(['value' => ['nullable', 'string']]);

        /** @var \Jegex\Koboi\Fields\Field&\Jegex\Koboi\Contracts\Previewable $field */
        $field = UpdateViewResource::make()
            ->newResourceWith($request)
            ->updateFields($request)
            ->whereInstanceOf(Previewable::class)
            ->findFieldByAttributeOrFail($request->field);

        return response()->json([
            'preview' => $field->previewFor($request->value),
        ]);
    }

    /**
     * Preview the field for "update-attached" request.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function updatePivot(ResourceUpdateOrUpdateAttachedRequest $request): JsonResponse
    {
        $request->validate(['value' => ['nullable', 'string']]);

        /** @var \Jegex\Koboi\Fields\Field&\Jegex\Koboi\Contracts\Previewable $field */
        $field = UpdatePivotFieldResource::make()
            ->newResourceWith($request)
            ->updatePivotFields($request, $request->relatedResource)
            ->whereInstanceOf(Previewable::class)
            ->findFieldByAttributeOrFail($request->field);

        return response()->json([
            'preview' => $field->previewFor($request->value),
        ]);
    }
}
