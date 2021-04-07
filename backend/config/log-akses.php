<?php

return array(
    'namespace'           => '*',
    'blacklist'           => ['App\Models\RemoteLog'],
    'guards'              => ['akun', 'ptk'],
    'indexed'             => ['instansi_id', 'ptk_id'],
    'filter-url'          => ['__clockwork*'],
    'always-log-url'      => ['api*'],
    'always-log-response' => true,
);
