<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

        'akun-foto' => [
            'driver'   => 'ftp',
            'passive'  => env('AKUN_FOTO_PASSIVE', env('FTP_PASSIVE', true)),
            'host'     => env('AKUN_FOTO_HOST', env('FTP_HOST', '172.17.1.20')),
            'username' => env('AKUN_FOTO_USER', env('FTP_USER', 'webmaster')),
            'password' => env('AKUN_FOTO_PASS', env('FTP_PASS', 'w3bmaster')),
            'path'     => env('AKUN_FOTO_PATH', '/upload/gpo/foto-akun'),
            'url'      => env('AKUN_FOTO_URL', 'https://upload.dev.siap.id/gpo/foto-akun'),
        ],

        'instansi-foto' => [
            'driver'   => 'ftp',
            'passive'  => env('INSTANSI_FOTO_PASSIVE', env('FTP_PASSIVE', true)),
            'host'     => env('INSTANSI_FOTO_HOST', env('FTP_HOST', '172.17.1.20')),
            'username' => env('INSTANSI_FOTO_USER', env('FTP_USER', 'webmaster')),
            'password' => env('INSTANSI_FOTO_PASS', env('FTP_PASS', 'w3bmaster')),
            'path'     => env('INSTANSI_FOTO_PATH', '/upload/gpo/foto-instansi'),
            'url'      => env('INSTANSI_FOTO_URL', 'https://upload.dev.siap.id/gpo/foto-instansi'),
        ],

        'lpd-berkas' => [
            'driver'   => 'ftp',
            'passive'  => env('LPD_BERKAS_PASSIVE', env('FTP_PASSIVE', true)),
            'host'     => env('LPD_BERKAS_HOST', env('FTP_HOST', '172.17.1.20')),
            'username' => env('LPD_BERKAS_USER', env('FTP_USER', 'webmaster')),
            'password' => env('LPD_BERKAS_PASS', env('FTP_PASS', 'w3bmaster')),
            'path'     => env('LPD_BERKAS_PATH', '/upload/gpo/paud/lpd-berkas'),
            'url'      => env('LPD_BERKAS_URL', 'https://upload.dev.siap.id/gpo/paud/lpd-berkas'),
        ],

        'pengajar-berkas' => [
            'driver'   => 'ftp',
            'passive'  => env('PENGAJAR_BERKAS_PASSIVE', env('FTP_PASSIVE', true)),
            'host'     => env('PENGAJAR_BERKAS_HOST', env('FTP_HOST', '172.17.1.20')),
            'username' => env('PENGAJAR_BERKAS_USER', env('FTP_USER', 'webmaster')),
            'password' => env('PENGAJAR_BERKAS_PASS', env('FTP_PASS', 'w3bmaster')),
            'path'     => env('PENGAJAR_BERKAS_PATH', '/upload/gpo/paud/pengajar-berkas'),
            'url'      => env('PENGAJAR_BERKAS_URL', 'https://upload.dev.siap.id/gpo/paud/pengajar-berkas'),
        ],

        'pembimbing-berkas' => [
            'driver'   => 'ftp',
            'passive'  => env('PEMBIMBING_BERKAS_PASSIVE', env('FTP_PASSIVE', true)),
            'host'     => env('PEMBIMBING_BERKAS_HOST', env('FTP_HOST', '172.17.1.20')),
            'username' => env('PEMBIMBING_BERKAS_USER', env('FTP_USER', 'webmaster')),
            'password' => env('PEMBIMBING_BERKAS_PASS', env('FTP_PASS', 'w3bmaster')),
            'path'     => env('PEMBIMBING_BERKAS_PATH', '/upload/gpo/paud/pembimbing-berkas'),
            'url'      => env('PEMBIMBING_BERKAS_URL', 'https://upload.dev.siap.id/gpo/paud/pembimbing-berkas'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
