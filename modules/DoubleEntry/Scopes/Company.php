<?php

namespace Modules\DoubleEntry\Scopes;

use App;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Company implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $company_id = session('company_id');

        if (empty($company_id)) {
            return;
        }

        $table = $model->getTable();

        // Remove for specific tables
        $remove_tables = ['double_entry_classes', 'double_entry_types'];

        if (!in_array($table, $remove_tables)) {
            return;
        }

        // Remove company_id from query
        $this->remove($builder, 'company_id');
    }

    /**
     * Remove scope from the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     * @param $column
     * @return void
     */
    public function remove($builder, $column)
    {
        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where) {
            if (empty($where) || empty($where['column'])) {
                continue;
            }

            if (strstr($where['column'], '.')) {
                $whr = explode('.', $where['column']);

                $where['column'] = $whr[1];
            }

            if ($where['column'] != $column) {
                continue;
            }

            unset($query->wheres[$key]);

            $query->wheres = array_values($query->wheres);
        }
    }
}
