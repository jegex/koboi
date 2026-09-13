<?php

namespace Jegex\Koboi\Fields;

use Jegex\Koboi\Http\Requests\NovaRequest;

trait SupportsDependentFields
{
    /**
     * List of field dependencies.
     *
     * @var array<int, Dependent>
     */
    protected $fieldDependencies = [];

    /**
     * Register depends on to a field.
     *
     * @param  Field|array<int, string|Field>|string  $attributes
     * @param  (callable(static, NovaRequest, FormData):(void))|class-string  $mixin
     * @return $this
     */
    public function dependsOn(Field|array|string $attributes, callable|string $mixin)
    {
        $this->fieldDependencies[] = new Dependent($attributes, $mixin);

        return $this;
    }

    /**
     * Register depends on to a field on creating request.
     *
     * @param  Field|array<int, string|Field>|string  $attributes
     * @param  (callable(static, NovaRequest, FormData):(void))|class-string  $mixin
     * @return $this
     */
    public function dependsOnCreating(Field|array|string $attributes, callable|string $mixin)
    {
        $this->fieldDependencies[] = new Dependent($attributes, $mixin, 'create');

        return $this;
    }

    /**
     * Register depends on to a field on updating request.
     *
     * @param  string|Field|array<int, string|Field>  $attributes
     * @param  (callable(static, NovaRequest, FormData):(void))|class-string  $mixin
     * @return $this
     */
    public function dependsOnUpdating($attributes, $mixin)
    {
        $this->fieldDependencies[] = new Dependent($attributes, $mixin, 'update');

        return $this;
    }
}
