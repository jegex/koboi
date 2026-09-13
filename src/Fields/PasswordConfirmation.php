<?php

namespace Jegex\Koboi\Fields;

use Illuminate\Database\Eloquent\Model;
use Jegex\Koboi\Http\Requests\NovaRequest;
use Jegex\Koboi\Support\Fluent;

class PasswordConfirmation extends Password
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'password-field';

    /**
     * Create a new field.
     *
     * @param  \Stringable|string  $name
     * @param  string|callable|null  $attribute
     * @param  (callable(mixed, mixed, ?string):(mixed))|null  $resolveCallback
     */
    public function __construct($name, mixed $attribute = null, ?callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->onlyOnForms();
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  Model|Fluent  $model
     */
    #[\Override]
    protected function fillAttribute(NovaRequest $request, string $requestAttribute, object $model, string $attribute): void
    {
        //
    }
}
