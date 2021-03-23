<?php

return [

    'path'    => [
        /*
        |--------------------------------------------------------------------------
        | Loading option of existing model
        |--------------------------------------------------------------------------
        | false = don't load existing model
        | null  = load from App/Models
        | other path = load from there
        */
        'model'  => null,

        /*
        |--------------------------------------------------------------------------
        | Path to save resulting model file
        |--------------------------------------------------------------------------
        | null = save to storage/model
        | other path = save to there
        */
        'result' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Limit generate table
    |--------------------------------------------------------------------------
    |   ['table-1', 'table-2', 'table-n']
    */
    'only'    => [
        'akun',
        'akun_instansi',
        'instansi',
        'm_group',
        'paud_akses',
        'paud_group_akses',
        'ptk',
        'remote_log',
    ],

    /*
    |--------------------------------------------------------------------------
    | Table to exclude
    |--------------------------------------------------------------------------
    |   ['failed_jobs', 'migrations', 'password_resets']
    */
    'except'  => ['failed_jobs', 'migrations', 'password_resets'],

    /*
    |--------------------------------------------------------------------------
    | Default Configuration
    |--------------------------------------------------------------------------
    |   [
    |       'cast' => [
    |           'fld_*' => 'date:Y-m-d',
    |           'field' => 'date:H:i:s',
    |       ],
    |       'fill_pkey' => false,
    |   ]
    */
    'default' => [],

    /*
    |--------------------------------------------------------------------------
    | Table configurtion override
    |--------------------------------------------------------------------------
    |   [
    |       'table_name' => [
    |           'base'  => 'Illuminate\Database\Eloquent\Model',
    |           'base'  => ['Illuminate\Foundation\Auth\User', 'Authenticatable'],
    |
    |           'class' => 'TableName',
    |           'gramar' => [
    |               'singular' => 'table_name',
    |               'plural'   => 'table_names',
    |           ],
    |
    |           'const' => ['primary_key', 'field'],
    |           'cast' => [
    |               'column' => 'custom_type',
    |           ],
    |           'fill_pkey' => true,
    |       ],
    |   ]
    */
    'options' => [
        'akun' => [
            'base' => ['Illuminate\Foundation\Auth\User', 'Authenticatable'],
        ],

        'm_group' => [
            'const' => ['k_group', 'singkat'],
        ],

        'paud_akses' => [
            'class'  => 'PaudAkses',
            'gramar' => [
                'singular' => 'paud_akses',
                'plural'   => 'paud_akseses',
            ],
        ],

        'paud_group_akses' => [
            'class'  => 'PaudGroupAkses',
            'gramar' => [
                'singular' => 'paud_group_akses',
                'plural'   => 'paud_group_akseses',
            ],
        ],

        'ptk' => [
            'base' => ['Illuminate\Foundation\Auth\User', 'Authenticatable'],
        ],
    ],
];
