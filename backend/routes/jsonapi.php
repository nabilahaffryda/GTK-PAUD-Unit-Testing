<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
use CloudCreativity\LaravelJsonApi\Routing\RelationshipsRegistration;
use CloudCreativity\LaravelJsonApi\Routing\RouteRegistrar as Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

JsonApi::register('v1', ['namespace' => 'JsonApi'], function (Api $api) {
    $api->resource('akuns')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations
                ->hasMany('akun_instansis', 'akun')
                ->readOnly();

            $relations
                ->hasMany('paud_admins', 'akun')
                ->readOnly();
        })
        ->readOnly();

    $api->resource('akun_instansis')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations
                ->hasOne('akun', 'akun_instansis')
                ->readOnly();

            $relations
                ->hasOne('instansi', 'akun_instansis')
                ->readOnly();

            $relations
                ->hasOne('m_group', 'akun_instansis')
                ->readOnly();
        })
        ->readOnly();

    $api->resource('instansis')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations
                ->hasMany('akun_instansis', 'instansi')
                ->readOnly();

            $relations
                ->hasMany('paud_admins', 'instansi')
                ->readOnly();

            $relations
                ->hasMany('paud_instansis', 'instansi')
                ->readOnly();

            $relations
                ->hasMany('paud_instansi_berkases', 'instansi')
                ->readOnly();
        })
        ->readOnly();

    $api->resource('m_berkas_pauds')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations
                ->hasMany('paud_instansi_berkases', 'm_berkas_paud')
                ->readOnly();
        })
        ->readOnly();

    $api->resource('m_groups')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations
                ->hasMany('akun_instansis', 'm_group')
                ->readOnly();

            $relations
                ->hasMany('paud_admins', 'm_group')
                ->readOnly();

            $relations
                ->hasMany('paud_group_akseses', 'm_group')
                ->readOnly();
        })
        ->readOnly();

    $api->resource('m_verval_pauds')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations
                ->hasMany('paud_instansis', 'm_verval_paud')
                ->readOnly();
        })
        ->readOnly();

    $api->resource('paud_admins')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations
                ->hasOne('akun', 'paud_admins')
                ->readOnly();

            $relations
                ->hasOne('instansi', 'paud_admins')
                ->readOnly();

            $relations
                ->hasOne('m_group', 'paud_admins')
                ->readOnly();
        })
        ->readOnly();

    $api->resource('paud_akseses')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations
                ->hasMany('paud_group_akseses', 'paud_akses')
                ->readOnly();
        })
        ->readOnly();

    $api->resource('paud_group_akseses')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations
                ->hasOne('m_group', 'paud_group_akseses')
                ->readOnly();

            $relations
                ->hasOne('paud_akses', 'paud_group_akseses')
                ->readOnly();
        })
        ->readOnly();

    $api->resource('paud_instansis')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations
                ->hasOne('instansi', 'paud_instansis')
                ->readOnly();

            $relations
                ->hasOne('m_verval_paud', 'paud_instansis')
                ->readOnly();

            $relations
                ->hasMany('paud_instansi_berkases', 'paud_instansi')
                ->readOnly();
        })
        ->readOnly();

    $api->resource('paud_instansi_berkases')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations
                ->hasOne('instansi', 'paud_instansi_berkases')
                ->readOnly();

            $relations
                ->hasOne('m_berkas_paud', 'paud_instansi_berkases')
                ->readOnly();

            $relations
                ->hasOne('paud_instansi', 'paud_instansi_berkases')
                ->readOnly();
        })
        ->readOnly();

    $api->resource('ptks')
        ->readOnly();
});
