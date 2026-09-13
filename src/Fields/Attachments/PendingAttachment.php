<?php

namespace Jegex\Koboi\Fields\Attachments;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Storage;
use Jegex\Koboi\Contracts\Storable;
use Jegex\Koboi\Fields\Field;

/**
 * @property string $attachment
 * @property string $disk
 */
class PendingAttachment extends Model
{
    use Prunable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nova_pending_field_attachments';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * The persist attachment model.
     *
     * @var class-string<Attachment>
     */
    protected static $persistModel = Attachment::class;

    /**
     * Get persist model instance.
     *
     * @return Attachment
     */
    public function getPersistModel()
    {
        return new static::$persistModel;
    }

    /**
     * Persist the given draft's pending attachments.
     *
     * @param  Model  $model
     *
     * @phpstan-param Field&Storable  $field
     */
    public static function persistDraft(string $draftId, Storable $field, $model): void
    {
        static::where('draft_id', $draftId)->get()->each->persist($field, $model);
    }

    /**
     * Persist the pending attachment.
     *
     * @param  Model  $model
     *
     * @phpstan-param Field&Storable  $field
     */
    public function persist(Storable $field, $model): void
    {
        $disk = $field->getStorageDisk() ?? $field->getDefaultStorageDisk();

        static::$persistModel::create([
            'attachable_type' => $model->getMorphClass(),
            'attachable_id' => $model->getKey(),
            'attachment' => $this->attachment,
            'disk' => $disk,
            'url' => Storage::disk($disk)->url($this->attachment),
        ]);

        $this->delete();
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subDays(1));
    }

    /**
     * Prepare the model for pruning.
     */
    protected function pruning(): void
    {
        Storage::disk($this->disk)->delete($this->attachment);
    }

    /**
     * Purge the attachment.
     */
    public function purge(): void
    {
        $this->prune();
    }
}
