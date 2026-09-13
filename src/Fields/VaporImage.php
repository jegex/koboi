<?php

namespace Jegex\Koboi\Fields;

use Illuminate\Support\Facades\Storage;
use Jegex\Koboi\Http\Requests\NovaRequest;

class VaporImage extends VaporFile
{
    use PresentsImages;

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = true;

    /**
     * Create a new field.
     *
     * @param  \Stringable|string  $name
     * @param  string|callable|null  $attribute
     * @param  (callable(NovaRequest, object, string, string, ?string, ?string):(mixed))|null  $storageCallback
     */
    public function __construct($name, mixed $attribute = null, ?callable $storageCallback = null)
    {
        parent::__construct($name, $attribute, $storageCallback);

        $this->acceptedTypes('image/*');

        $this->thumbnail(function () {
            return $this->value ? Storage::disk($this->getStorageDisk())->temporaryUrl($this->value, now()->addMinutes(5)) : null;
        })->preview(function () {
            return $this->value ? Storage::disk($this->getStorageDisk())->temporaryUrl($this->value, now()->addMinutes(5)) : null;
        });
    }

    /**
     * Prepare the field element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), $this->imageAttributes());
    }
}
