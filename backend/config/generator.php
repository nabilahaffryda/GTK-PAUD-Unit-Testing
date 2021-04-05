<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Limit generate table
    |--------------------------------------------------------------------------
    |   ['table-1', 'table-2', 'table-n']
    */
    'only'    => [],

    /*
    |--------------------------------------------------------------------------
    | Table to exclude
    |--------------------------------------------------------------------------
    |   ['failed_jobs', 'migrations', 'password_resets']
    */
    'except'  => ['failed_jobs', 'migrations', 'password_resets', 'clockwork'],

    /*
    |--------------------------------------------------------------------------
    | JSON:API Default Configuration
    |--------------------------------------------------------------------------
    */
    'model'   => [
        'path'    => [
            /*
            |--------------------------------------------------------------------------
            | Path to existing file to be use as reference
            |--------------------------------------------------------------------------
            | false = don't load existing model
            | null  = load from App/Models
            | other path = load from there
            */
            'reference' => null,

            /*
            |--------------------------------------------------------------------------
            | Path to save resulting file
            |--------------------------------------------------------------------------
            | null = save to storage
            | other path = save to there
            */
            'result'    => null,
        ],

        /*
        |--------------------------------------------------------------------------
        | Limit generate model file
        |--------------------------------------------------------------------------
        |   ['table-1', 'table-2', 'table-n']
        */
        'only'    => [
            '*_paud',
            'akun',
            'akun_instansi',
            'instansi',
            'm_group',
            'paud_*',
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
        |       'fill_key' => true,
        |   ]
        */
        'default' => [
            'fill_key' => true,
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | JSON:API Default Configuration
    |--------------------------------------------------------------------------
    */
    'jsonapi' => [
        'path'    => [
            /*
            |--------------------------------------------------------------------------
            | Path to existing file to be use as reference
            |--------------------------------------------------------------------------
            | false = don't load existing model
            | null  = load from App/JsonApi
            | other path = load from there
            */
            'reference' => false,

            /*
            |--------------------------------------------------------------------------
            | Path to save resulting file
            |--------------------------------------------------------------------------
            | null = save to storage
            | other path = save to there
            */
            'result'    => null,
        ],

        /*
        |--------------------------------------------------------------------------
        | Limit generate json api file
        |--------------------------------------------------------------------------
        |   ['table-1', 'table-2', 'table-n']
        */
        'only'    => [
            '*_paud',
            'akun',
            'akun_instansi',
            'instansi',
            'm_group',
            'paud_*',
            'ptk',
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
        */
        'default' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Table configuration override
    |--------------------------------------------------------------------------
    | <table-name>.class : Class name (default use Str::studly(table))
    | <table-name>.gramar.singular : Singular name (default use Str::singular(table))
    | <table-name>.gramar.plural : Plural table name (default use Str::plural(Str::singular(table)))
    |
    | <table-name>.model.base : Base used for model, can be string or array
    |   - string : 'Illuminate\Database\Eloquent\Model'
    |   - array  : ['Illuminate\Foundation\Auth\User', 'Authenticatable']
    |
    | <table-name>.model.const : Dump table content as class const
    | <table-name>.model.const.0 : Field to be use as value of const
    | <table-name>.model.const.1 : Field to be use as const names
    |
    | <table-name>.model.cast : Override column type
    | <table-name>.model.cast.<column> : String to use as column type
    |
    | <table-name>.model.fill_key : Boolean, if true primary key will included in fillable
    |
    |
    | <table-name>.jsonapi.child_data : Include child table in related field, default only parent table
    | <table-name>.jsonapi.child_data.0 : Child table name
    | <table-name>.jsonapi.child_data.<...> : Any other child table name
    */
    'options' => [
        'akun' => [
            'model' => [
                'base' => ['Illuminate\Foundation\Auth\User', 'Authenticatable'],
            ],
        ],

        'm_group' => [
            'model' => [
                'const' => ['k_group', 'singkat'],
            ],
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

        'paud_instansi_berkas' => [
            'class'  => 'PaudInstansiBerkas',
            'gramar' => [
                'singular' => 'paud_instansi_berkas',
                'plural'   => 'paud_instansi_berkases',
            ],
        ],

        'ptk' => [
            'model' => [
                'base' => ['Illuminate\Foundation\Auth\User', 'Authenticatable'],
            ],
        ],
    ],
];
