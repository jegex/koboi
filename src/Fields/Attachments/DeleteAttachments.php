<?php

namespace Jegex\Koboi\Fields\Attachments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Jegex\Koboi\Contracts\Storable;
use Jegex\Koboi\Fields\Field;

class DeleteAttachments
{
    /**
     * The field instance.
     *
     * @var Field&Storable
     */
    public $field;

    /**
     * The attachment model.
     *
     * @var class-string<Attachment>
     */
    public static $model = Attachment::class;

    /**
     * Create a new class instance.
     *
     * @param  Field&Storable  $field
     */
    public function __construct($field)
    {
        $this->field = $field;
    }

    /**
     * Delete the attachments associated with the field.
     *
     * @param  Model  $model
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
