<?php

namespace App\Services;

use DB;
use Illuminate\Database\QueryException;

class MasterService
{
    public function fetch($tables, $fields, $filters)
    {
        $result = [];

        foreach ($tables as $index => $table) {
            if (substr($table, 0, 2) != 'm_') {
                $table = "m_$table";
            }

            $field  = $fields[$index] ?? ['keterangan'];
            $filter = $filters[$index] ?? [];

            try {
                $info = DB::select("SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'");
            } catch (QueryException) {
                $result[$index] = [];
                continue;
            }

            $key = $info[0]->Column_name;

            $query = DB::table($table)
                ->select(array_unique(array_merge([$key], $field)))
                ->when($table == 'm_kecamatan' || $table == 'm_kelurahan', function ($query) {
                    $query->where('kode_dagri', '>', 0);
                });

            if ($filter) {
                foreach ($filter as $column => $value) {
                    if (is_array($value)) {
                        if ($value == array_values($value)) {
                            $query->whereIn($column, $value);
                        } elseif (isset($value['val'])) {
                            $query->where($column, $value['op'] ?? '=', $value['val']);
                        }
                    } else {
                        $query->where($column, $value);
                    }
                }
            } else {
                $query->limit(1000);
            }

            $res = $query->get();

            if (count($field) == 1) {
                $result[$index] = $res->pluck($field[0], $key);
            } else {
                $result[$index] = $res->keyBy($key)->all();
            }
        }

        return $result;
    }
}
