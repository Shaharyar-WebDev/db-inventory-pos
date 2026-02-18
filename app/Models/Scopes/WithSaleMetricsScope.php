<?php
namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class WithSaleMetricsScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->withAggregate('items as cogs', 'SUM(qty * cost)')
            ->withAggregate('items as revenue', 'SUM(qty * rate)');
    }
}
