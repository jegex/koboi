<?php

namespace Jegex\Koboi\Fields\Attachments;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jegex\Koboi\Contracts\Storable;
use Jegex\Koboi\Fields\Field;

class StorePendingAttachment
{
    /**
     * The field instance.
     *
     * @var Field&Storable
     */
    public $field;

    /**
     * The pending attachment model.
     *
     * @var class-string<PendingAttachment>
     */
    public static $model = PendingAttachment::class;

    /**
     * Create a new invokable instance.
     *
     *
     * @phpstan-param Field&Storable $field
     *
     * @return void
     */
    public function __construct(Storable $field)
    {
        $this->field = $field;
    }

    /**
     * Attach a pending attachment to the field.
     *
     * @return array{path: string, url: string}
     */
    public function __invoke(Request $request): array
    {
        $request->validate([
            'attachment' => ['required', 'file'],
        ]);

        $disk = $this->field->getStorageDisk() ?? $this->field->getDefaultStorageDisk();

        static::$model::create([
            'draft_id' => $request->draftId,
            'attachment' => $path = $request->file('attachment')->store($this->field->getStorageDir(), $disk),
            'disk' => $disk,
        ]);

        return [
            'path' => $path,
            'url' => Storage::disk($disk)->url($path),
        ];
    }
}
