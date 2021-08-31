<?php

namespace App\GraphQL\Mutations\Ptk;

use App\Models\MKonfirmasiPaud;
use App\Models\PaudKelasPeserta;
use Exception;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class KelasPeserta
{
    /**
     * @throws Exception
     */
    public function konfirmasiSetuju($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $kelasPesertaId = $args['kelasPesertaId'];

        $kelasPeserta = PaudKelasPeserta::findOrFail($kelasPesertaId);

        if ($kelasPeserta->k_konfirmasi_paud && $kelasPeserta->k_konfirmasi_paud != MKonfirmasiPaud::BELUM_KONFIRMASI) {
            throw new Exception('Anda telah melakukan konfirmasi');
        }

        $kelasPeserta->k_konfirmasi_paud = MKonfirmasiPaud::BERSEDIA;
        $kelasPeserta->save();

        return $kelasPeserta;
    }

    /**
     * @throws Exception
     */
    public function konfirmasiTolak($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $kelasPesertaId = $args['kelasPesertaId'];

        $kelasPeserta = PaudKelasPeserta::findOrFail($kelasPesertaId);

        if ($kelasPeserta->k_konfirmasi_paud && $kelasPeserta->k_konfirmasi_paud != MKonfirmasiPaud::BELUM_KONFIRMASI) {
            throw new Exception('Anda telah melakukan konfirmasi');
        }

        $kelasPeserta->k_konfirmasi_paud = MKonfirmasiPaud::TIDAK_BERSEDIA;
        $kelasPeserta->save();

        return $kelasPeserta;
    }

    /**
     * @throws Exception
     */
    public function konfirmasiBatal($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $kelasPesertaId = $args['kelasPesertaId'];

        $kelasPeserta = PaudKelasPeserta::findOrFail($kelasPesertaId);

        if ($kelasPeserta->k_konfirmasi_paud && !in_array($kelasPeserta->k_konfirmasi_paud, [MKonfirmasiPaud::BERSEDIA, MKonfirmasiPaud::TIDAK_BERSEDIA])) {
            throw new Exception('Anda belum melakukan konfirmasi');
        }

        $kelasPeserta->k_konfirmasi_paud = MKonfirmasiPaud::BELUM_KONFIRMASI;
        $kelasPeserta->save();

        return $kelasPeserta;
    }
}
