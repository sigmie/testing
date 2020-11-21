<?php

declare(strict_types=1);

namespace Sigmie\Tests\Unit\Import;

use JsonMachine\JsonMachine;
use PHPUnit\Framework\TestCase;

class Foo extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function foo(): void
    {
        $indexname = "awesome_index_20201001";
        $es = 'http://' . getenv('ES_HOST') . ':' . getenv('ES_PORT');

        exec("curl --request PUT \  --url {$es}/{$indexname}");

        $machine = JsonMachine::fromFile('https://gist.githubusercontent.com/nicoorfi/e1e70646515e983f9563fbcb174f52ff/raw/4cd23e1daf1bbac5703307b5411c7eafb9ab534d/docs.sigmie.content.json');

        foreach ($machine as $page) {
            foreach ($page as $record) {
                $data = json_encode($record);
                exec("curl --request POST --url {$es}/{$indexname}/_doc --header 'content-type: application/json' --data {$data}");
            }
        }

        $this->assertTrue(false);
    }
}
