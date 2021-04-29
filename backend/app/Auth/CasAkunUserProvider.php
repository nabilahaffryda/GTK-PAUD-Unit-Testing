<?php

namespace App\Auth;

use App\Models\Akun;
use App\Services\AkunService;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Database\Eloquent\Model;

class CasAkunUserProvider extends CasUserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param mixed $identifier
     * @return UserContract|Model|null
     */
    public function retrieveById($identifier)
    {
        /** @var Akun $akun */
        $akun = parent::retrieveById($identifier);
        if (!$akun) {
            return null;
        }

        $akunInstansi = app(AkunService::class)->akunInstansi($akun);
        if (!$akunInstansi || !$akunInstansi->is_aktif) {
            return null;
        }

        return $akun;
    }
}
