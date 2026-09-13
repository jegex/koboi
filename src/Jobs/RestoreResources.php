<?php

namespace Jegex\Koboi\Jobs;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Jegex\Koboi\Http\Requests\RestoreLensResourceRequest;
use Jegex\Koboi\Http\Requests\RestoreResourceRequest;
use Jegex\Koboi\Nova;

class RestoreResources
{
    use Dispatchable;

    /**
     * Create a new job instance.
     *
     * @param  class-string<\Jegex\Koboi\Resource>|null  $resourceClass
     */
    public function __construct(
        public RestoreResourceRequest|RestoreLensResourceRequest $request,
        public ?string $resourceClass = null
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->request->chunks(150, function ($models) {
            /** @var Collection<array-key, Model> $models */
            $models->each(function ($model) {
                if (! \is_null($this->resourceClass)) {
                    $this->resourceClass::beforeRestore($this->request, $model);
                }

                /** @phpstan-ignore method.notFound */
                $model->restore();

                if (! \is_null($this->resourceClass)) {
                    $this->resourceClass::afterRestore($this->request, $model);
                }

                Nova::usingActionEvent(function ($actionEvent) use ($model) {
                    $actionEvent->insert(
                        $actionEvent->forResourceRestore(Nova::user($this->request), collect([$model]))
                            ->map->getAttributes()->all()
                    );
                });
            });
        });
    }
}
