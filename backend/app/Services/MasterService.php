<?php

namespace App\Services;

use Eloquent;
use Str;

class MasterService
{
    public function fetch($tables, $fields, $filters)
    {
        $result = [];

        foreach ($tables as $index => $table) {
            if (substr($table, 0, 2) != 'm_') {
                $result[$index] = [];
                continue;
            }

            $field  = $fields[$index] ?? ['keterangan'];
            $filter = $filters[$index] ?? [];

            /** @var Eloquent $class */
            $class = '\\App\Models\\' . Str::camel($table);

            $key = (new $class())->getKeyName();

            $q = $class::select(array_unique(array_merge([$key], $field)));

            if ($filter) {
                foreach ($filter as $column => $value) {
                    if (is_array($value)) {
                        if ($value == array_values($value)) {
                            $q->whereIn($column, $value);
                        } elseif (isset($value['val'])) {
                            $q->where($column, $value['op'] ?? '=', $value['val']);
                        }
                    } else {
                        $q->where($column, $value);
                    }
                }
            } else {
                $q->limit(1000);
            }

            $res = $q->get();

            if (count($field) == 1) {
                $result[$index] = $res->pluck($field[0], $key);
            } else {
                $result[$index] = $res->keyBy($key)->all();
            }
        }

        return $result;
    }
}
