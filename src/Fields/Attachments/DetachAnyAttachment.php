<?php

namespace Jegex\Koboi\Fields\Attachments;

use Jegex\Koboi\Http\Requests\NovaRequest;

class DetachAnyAttachment
{
    /**
     * Delete any attachments from the field.
     */
    public function __invoke(NovaRequest $request): void
    {
        \call_user_func(new DetachAttachment, $request);
        \call_user_func(new DetachPendingAttachment, $request);
    }
}
