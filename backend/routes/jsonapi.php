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

JsonApi::register('v1', [
    'namespace'  => 'App\\Http\\Controllers\\JsonApi',
    'middleware' => ['auth:akun,ptk'],
], function (Api $api) {
    $api->resource('akuns')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasOne('kota', 'akuns')->readOnly();
            $relations->hasOne('instansi_kota', 'instansi_kota_akuns')->readOnly();
            $relations->hasOne('instansi_propinsi', 'instansi_propinsi_akuns')->readOnly();
            $relations->hasOne('m_propinsi', 'akuns')->readOnly();
            $relations->hasMany('akun_instansis', 'akun')->readOnly();
            $relations->hasMany('paud_admins', 'akun')->readOnly();
        })->readOnly();

    $api->resource('akun_instansis')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasOne('akun', 'akun_instansis')->readOnly();
            $relations->hasOne('instansi', 'akun_instansis')->readOnly();
            $relations->hasOne('m_group', 'akun_instansis')->readOnly();
        })->readOnly();

    $api->resource('instansis')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasOne('m_jenis_instansi', 'instansis')->readOnly();
            $relations->hasOne('kota', 'instansis')->readOnly();
            $relations->hasOne('m_propinsi', 'instansis')->readOnly();
            $relations->hasMany('akun_instansis', 'instansi')->readOnly();
            $relations->hasMany('paud_admins', 'instansi')->readOnly();
            $relations->hasMany('paud_instansis', 'instansi')->readOnly();
            $relations->hasMany('paud_instansi_berkases', 'instansi')->readOnly();
        })->readOnly();

    $api->resource('m_berkas_pauds')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasMany('paud_instansi_berkases', 'm_berkas_paud')->readOnly();
        })->readOnly();

    $api->resource('m_groups')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasOne('m_jenis_instansi', 'm_groups')->readOnly();
            $relations->hasMany('akun_instansis', 'm_group')->readOnly();
            $relations->hasMany('paud_admins', 'm_group')->readOnly();
            $relations->hasMany('paud_group_akseses', 'm_group')->readOnly();
        })->readOnly();

    $api->resource('m_jenis_instansis')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasMany('instansis', 'm_jenis_instansi')->readOnly();
            $relations->hasMany('m_groups', 'm_jenis_instansi')->readOnly();
        })
        ->readOnly();

    $api->resource('kotas')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasOne('m_propinsi', 'kotas')->readOnly();
            $relations->hasMany('akuns', 'kota')->readOnly();
            $relations->hasMany('instansi_kota_akuns', 'instansi_kota')->readOnly();
            $relations->hasMany('instansis', 'kota')->readOnly();
            $relations->hasMany('ptks', 'kota')->readOnly();
        })
        ->readOnly();

    $api->resource('m_propinsis')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasMany('instansi_propinsi_akuns', 'instansi_propinsi')->readOnly();
            $relations->hasMany('akuns', 'm_propinsi')->readOnly();
            $relations->hasMany('instansis', 'm_propinsi')->readOnly();
            $relations->hasMany('kotas', 'm_propinsi')->readOnly();
            $relations->hasMany('ptks', 'm_propinsi')->readOnly();
        })
        ->readOnly();

    $api->resource('m_status_emails')
        ->readOnly();

    $api->resource('m_verval_pauds')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasMany('paud_instansis', 'm_verval_paud')->readOnly();
        })->readOnly();

    $api->resource('paud_admins')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasOne('akun', 'paud_admins')->readOnly();
            $relations->hasOne('instansi', 'paud_admins')->readOnly();
            $relations->hasOne('m_group', 'paud_admins')->readOnly();
        })->readOnly();

    $api->resource('paud_akseses')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasMany('paud_group_akseses', 'paud_akses')->readOnly();
        })->readOnly();

    $api->resource('paud_group_akseses')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasOne('m_group', 'paud_group_akseses')->readOnly();
            $relations->hasOne('paud_akses', 'paud_group_akseses')->readOnly();
        })->readOnly();

    $api->resource('paud_instansis')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasOne('instansi', 'paud_instansis')->readOnly();
            $relations->hasOne('m_verval_paud', 'paud_instansis')->readOnly();
            $relations->hasMany('paud_instansi_berkases', 'paud_instansi')->readOnly();
        })->readOnly();

    $api->resource('paud_instansi_berkases')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasOne('instansi', 'paud_instansi_berkases')->readOnly();
            $relations->hasOne('m_berkas_paud', 'paud_instansi_berkases')->readOnly();
            $relations->hasOne('paud_instansi', 'paud_instansi_berkases')->readOnly();
        })->readOnly();

    $api->resource('ptks')
        ->relationships(function (RelationshipsRegistration $relations) {
            $relations->hasOne('kota', 'ptks')->readOnly();
            $relations->hasOne('m_propinsi', 'ptks')->readOnly();
        })
        ->readOnly();
});
