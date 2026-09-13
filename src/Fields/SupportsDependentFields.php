<?php

namespace Jegex\Koboi\Fields;

trait SupportsDependentFields
{
    /**
     * List of field dependencies.
     *
     * @var array<int, \Jegex\Koboi\Fields\Dependent>
     */
    protected $fieldDependencies = [];

    /**
     * Register depends on to a field.
     *
     * @param  \Jegex\Koboi\Fields\Field|array<int, string|\Jegex\Koboi\Fields\Field>|string  $attributes
     * @param  (callable(static, \Jegex\Koboi\Http\Requests\NovaRequest, \Jegex\Koboi\Fields\FormData):(void))|class-string  $mixin
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
     * @param  \Jegex\Koboi\Fields\Field|array<int, string|\Jegex\Koboi\Fields\Field>|string  $attributes
     * @param  (callable(static, \Jegex\Koboi\Http\Requests\NovaRequest, \Jegex\Koboi\Fields\FormData):(void))|class-string  $mixin
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
     * @param  string|\Jegex\Koboi\Fields\Field|array<int, string|\Jegex\Koboi\Fields\Field>  $attributes
     * @param  (callable(static, \Jegex\Koboi\Http\Requests\NovaRequest, \Jegex\Koboi\Fields\FormData):(void))|class-string  $mixin
     * @return $this
     */
    public function dependsOnUpdating($attributes, $mixin)
    {
        $this->fieldDependencies[] = new Dependent($attributes, $mixin, 'update');

        return $this;
    }
}
