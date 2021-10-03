<?php

return [
    'tahun'    => 2021,
    'angkatan' => 1,

    'diklat' => [
        'wkt-tutup-ajuan' => '2021-10-03 16:00:00',
    ],

    'elearning' => [
        'sync' => true,

        'instansi-id'     => [1, 2],
        'diklat-id'       => [1, 2],
        'jenis-diklat-id' => [1, 1],

        'group-id-petugas' => [
            1 => 4, // pengajar
            2 => 6, // pengajar tambahan
            3 => 7, // pembimbing praktik
            4 => 5, // admin kelas
        ],

        'group-id-peserta' => 8,
    ],
];
