<?php

namespace Jegex\Koboi\Fields\Attachments;

use Illuminate\Http\Request;

class DiscardPendingAttachments
{
    /**
     * The pending attachment model.
     *
     * @var class-string<PendingAttachment>
     */
    public static $model = PendingAttachment::class;

    /**
     * Discard pending attachments on the field.
     */
    public function __invoke(Request $request): void
    {
        static::$model::where('draft_id', $request->draftId)
            ->get()
            ->each->purge();
    }
}
