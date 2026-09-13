<?php

namespace Jegex\Koboi\Fields\Attachments;

use Jegex\Koboi\Http\Requests\NovaRequest;

class DetachAttachment
{
    /**
     * The attachment model.
     *
     * @var class-string<Attachment>
     */
    public static $model = Attachment::class;

    /**
     * Delete an attachment from the field.
     */
    public function __invoke(NovaRequest $request): void
    {
        static::$model::where('url', $request->attachmentUrl)
            ->get()
            ->each->purge();
    }
}
