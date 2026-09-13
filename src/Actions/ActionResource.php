<?php

namespace Jegex\Koboi\Actions;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Jegex\Koboi\Fields\DateTime;
use Jegex\Koboi\Fields\ID;
use Jegex\Koboi\Fields\KeyValue;
use Jegex\Koboi\Fields\MorphToActionTarget;
use Jegex\Koboi\Fields\Status;
use Jegex\Koboi\Fields\Text;
use Jegex\Koboi\Fields\Textarea;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Nova;
use Jegex\Koboi\Resource;

/**
 * @template TActionModel of \Jegex\Koboi\Actions\ActionEvent
 *
 * @extends \Jegex\Koboi\Resource<TActionModel>
 */
class ActionResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<TActionModel>
     */
    public static $model = ActionEvent::class;

    /**
     * The policy the resource corrsponds to.
     *
     * @var class-string|null
     */
    public static $policy = ActionResourcePolicy::class;

    /** {@inheritDoc} */
    public static $title = 'name';

    /** {@inheritDoc} */
    public static $with = ['target', 'user'];

    /** {@inheritDoc} */
    public static $globallySearchable = false;

    /** {@inheritDoc} */
    public static $polling = true;

    /** {@inheritDoc} */
    #[\Override]
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(Nova::__('ID'), 'id')->showOnPreview(),
            Text::make(Nova::__('Action Name'), 'name', static fn ($value) => Nova::__($value))->showOnPreview(),

            Text::make(Nova::__('Action Initiated By'), function () {
                return $this->user->name ?? $this->user->email ?? __('Nova User');
            })->showOnPreview(),

            MorphToActionTarget::make(Nova::__('Action Target'), 'target')->showOnPreview(),

            Status::make(Nova::__('Action Status'), 'status', static function ($value) {
                return transform($value, static fn ($value) => Nova::__(ucfirst($value)));
            })->loadingWhen([Nova::__('Waiting'), Nova::__('Running')])->failedWhen([Nova::__('Failed')]),

            $this->when(isset($this->original), static function () {
                return KeyValue::make(Nova::__('Original'), 'original')->showOnPreview();
            }),

            $this->when(isset($this->changes), static function () {
                return KeyValue::make(Nova::__('Changes'), 'changes')->showOnPreview();
            }),

            Textarea::make(Nova::__('Exception'), 'exception')->showOnPreview(),

            DateTime::make(Nova::__('Action Happened At'), 'created_at')->exceptOnForms()->showOnPreview(),
        ];
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function indexQuery(NovaRequest $request, Builder $query)
    {
        return $query->with('user');
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function availableForNavigation(Request $request)
    {
        return false;
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function searchable()
    {
        return false;
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function label()
    {
        return Nova::__('Action Events');
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function singularLabel()
    {
        return Nova::__('Action Event');
    }

    /** {@inheritDoc} */
    #[\Override]
    public static function uriKey()
    {
        return 'action-events';
    }
}
