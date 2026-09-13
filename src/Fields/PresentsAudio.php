<?php

namespace Jegex\Koboi\Fields;

use Jegex\Koboi\Http\Requests\NovaRequest;

trait PresentsAudio
{
    /**
     * The "preload" attribute callback.
     *
     * @var (callable(NovaRequest):string)|string|null
     */
    public $preloadAudioCallback;

    /**
     * Set "preload" option for the field.
     *
     * @param  (callable(NovaRequest):string)|string  $preloadAudioCallback
     * @return $this
     */
    public function preload(callable|string $preloadAudioCallback)
    {
        $this->preloadAudioCallback = $preloadAudioCallback;

        return $this;
    }

    /**
     * Return the attributes to present the image with.
     *
     * @return array{preload: string|null}
     */
    public function audioAttributes()
    {
        $request = app(NovaRequest::class);

        return [
            'preload' => value($this->preloadAudioCallback, $request),
        ];
    }
}
