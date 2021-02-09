<?php

return [

    'path'    => [
        /*
         * false = don't load existing model
         * null  = load from App/Models
         * other path = load from there
         */
        'model'  => false,

        /*
         * null = save to storage/model
         * other path = save to there
         */
        'result' => null,
    ],

    /*
     *  ['table-1', 'table-2', 'table-n']
     */
    'only'    => [
        'akun',
        'ptk'
    ],

    /*
     *  ['failed_jobs', 'migrations', 'password_resets']
     */
    'except'  => ['failed_jobs', 'migrations', 'password_resets'],

    /*
     * [
     *     'table-1' => [
     *         'base'  => 'Illuminate\Database\Eloquent\Model',
     *         'base'  => ['Illuminate\Foundation\Auth\User', 'Authenticatable'],
     *
     *         'const' => ['primary_key', 'field'],
     *     ],
     * ]
     *
     */
    'options' => [
        'akun' => [
            'base' => ['Illuminate\Foundation\Auth\User', 'Authenticatable'],
        ],
        'ptk' => [
            'base' => ['Illuminate\Foundation\Auth\User', 'Authenticatable'],
        ],
    ],
];
