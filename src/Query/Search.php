<?php

namespace Jegex\Koboi\Query;

use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Jegex\Koboi\Query\Search\Column;

class Search
{
    /**
     * Create a new search builder instance.
     */
    public function __construct(
        public EloquentBuilder $queryBuilder,
        public string $searchKeyword
    ) {
        //
    }

    /**
     * Get the raw results of the search.
     *
     * @param  class-string<\Jegex\Koboi\Resource>  $resourceClass
     * @param  array<int, string|Column>  $searchColumns
     */
    public function handle(string $resourceClass, array $searchColumns): EloquentBuilder
    {
        return $this->queryBuilder->where(function ($query) use ($searchColumns) {
            $connectionType = $query->getModel()->getConnection()->getDriverName();

            $columns = collect($searchColumns);

            $whereOperator = $columns->count() > 1 ? 'orWhere' : 'where';

            $columns->each(function ($column) use ($query, $connectionType, $whereOperator) {
                /** @phpstan-ignore booleanAnd.alwaysFalse */
                if ($column instanceof Column || (! \is_string($column) && \is_callable($column))) {
                    $column($query, $this->searchKeyword, $connectionType, $whereOperator);
                } else {
                    Column::from($column)->__invoke($query, $this->searchKeyword, $connectionType, $whereOperator);
                }
            });
        });
    }
}
