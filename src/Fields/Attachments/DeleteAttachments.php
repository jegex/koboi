<?php

namespace Jegex\Koboi\Fields\Attachments;

use Illuminate\Http\Request;

class DeleteAttachments
{
    /**
     * The field instance.
     *
     * @var \Jegex\Koboi\Fields\Field&\Jegex\Koboi\Contracts\Storable
     */
    public $field;

    /**
     * The attachment model.
     *
     * @var class-string<\Jegex\Koboi\Fields\Attachments\Attachment>
     */
    public static $model = Attachment::class;

    /**
     * Create a new class instance.
     *
     * @param  \Jegex\Koboi\Fields\Field&\Jegex\Koboi\Contracts\Storable  $field
     */
    public function __construct($field)
    {
        $this->field = $field;
    }

    /**
     * Delete the attachments associated with the field.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function __invoke(Request $request, $model): array
    {
        static::$model::query()
            ->where('attachable_type', $model->getMorphClass())
            ->where('attachable_id', $model->getKey())
            ->get()
            ->each->purge();

        return [$this->field->attribute => ''];
    }
}
