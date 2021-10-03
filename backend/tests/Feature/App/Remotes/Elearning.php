<?php

namespace Tests\Feature\App\Remotes;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class Elearning extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $data = [
            [
                "id"         => 1,
                "nama"       => "kemdikbud",
                "kode"       => "1",
                "keterangan" => "kemdikbud",
            ],
        ];

        Http::fake([
            'instansi' => Http::response($data),
        ]);

        $response = app(\App\Remotes\ElearningRemote::class)->instansiList();

        $this->assertEquals($data, $response);
    }
}
