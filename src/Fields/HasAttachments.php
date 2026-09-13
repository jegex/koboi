<?php

namespace Jegex\Koboi\Fields;

use Illuminate\Database\Eloquent\Model;
use Jegex\Koboi\Fields\Attachments\DeleteAttachments;
use Jegex\Koboi\Fields\Attachments\DetachAnyAttachment;
use Jegex\Koboi\Fields\Attachments\DiscardPendingAttachments;
use Jegex\Koboi\Fields\Attachments\PendingAttachment;
use Jegex\Koboi\Fields\Attachments\StorePendingAttachment;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Support\Fluent;

trait HasAttachments
{
    use Deletable;
    use Storable;

    /**
     * Indicates if the field should accept files.
     *
     * @var bool
     */
    public $withFiles = false;

    /**
     * The callback that should be executed to store file attachments.
     *
     * @var callable(NovaRequest):array{path: string, url: string}
     */
    public $attachCallback;

    /**
     * The callback that should be executed to delete persisted file attachments.
     *
     * @var (callable(NovaRequest):void)|DetachAnyAttachment
     */
    public $detachCallback;

    /**
     * The callback that should be executed to discard file attachments.
     *
     * @var callable(NovaRequest):void
     */
    public $discardCallback;

    /**
     * Specify the callback that should be used to store file attachments.
     *
     * @param  callable(NovaRequest):array{path: string, url: string}  $callback
     * @return $this
     */
    public function attach(callable $callback)
    {
        $this->withFiles = true;

        $this->attachCallback = $callback;

        return $this;
    }

    /**
     * Specify the callback that should be used to delete a single, persisted file attachment.
     *
     * @param  callable(NovaRequest):void  $callback
     * @return $this
     */
    public function detach(callable $callback)
    {
        $this->withFiles = true;

        $this->detachCallback = $callback;

        return $this;
    }

    /**
     * Specify the callback that should be used to discard pending file attachments.
     *
     * @return $this
     */
    public function discard(callable $callback)
    {
        $this->withFiles = true;

        $this->discardCallback = $callback;

        return $this;
    }

    /**
     * Specify the callback that should be used to delete the field.
     *
     * @param  callable(NovaRequest, mixed, ?string, ?string):mixed  $deleteCallback
     * @return $this
     */
    public function delete(callable $deleteCallback)
    {
        $this->withFiles = true;

        $this->deleteCallback = $deleteCallback;

        return $this;
    }

    /**
     * Specify that file uploads should be allowed.
     *
     * @return $this
     */
    public function withFiles(?string $disk = null, string $path = '/')
    {
        $this->withFiles = true;

        $this->disk($disk)->path($path);

        $this->attach(new StorePendingAttachment($this))
            ->detach(new DetachAnyAttachment)
            ->delete(new DeleteAttachments($this))
            ->discard(new DiscardPendingAttachments)
            ->prunable();

        return $this;
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  Model|Fluent  $model
     */
    protected function fillAttributeWithAttachment(NovaRequest $request, string $requestAttribute, object $model, string $attribute): ?callable
    {
        $callbacks = [];

        $maybeCallback = parent::fillAttribute($request, $requestAttribute, $model, $attribute);

        $attribute = str_contains($requestAttribute, '.') && $this->attribute !== $requestAttribute
            ? "{$requestAttribute}DraftId"
            : str_replace('.', '->', "{$this->attribute}DraftId");

        if (\is_callable($maybeCallback)) {
            $callbacks[] = $maybeCallback;
        }

        if ($request->{$attribute} && $this->withFiles) {
            $callbacks[] = function () use ($request, $model, $attribute) {
                PendingAttachment::persistDraft(
                    $request->{$attribute},
                    $this,
                    $model
                );
            };
        }

        if (\count($callbacks)) {
            return static function () use ($callbacks) {
                collect($callbacks)->each->__invoke();
            };
        }

        return null;
    }
}
