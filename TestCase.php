<?php

declare(strict_types=1);

namespace Sigmie\Testing;

use Carbon\Carbon;
use Sigmie\Base\Http\ElasticsearchConnection;
use Sigmie\Document\Actions as DocumentActions;
use Sigmie\Http\JSONClient;
use Sigmie\Sigmie;
use Sigmie\Index\Actions as IndexAction;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use ClearElasticsearch;
    use Assertions;
    use IndexAction;
    use DocumentActions;

    protected Sigmie $sigmie;

    protected JSONClient $jsonClient;

    public function setUp(): void
    {
        parent::setUp();

        $this->jsonClient = JSONClient::create(['localhost:9200']);

        $this->elasticsearchConnection = new ElasticsearchConnection($this->jsonClient);

        $this->clearElasticsearch($this->elasticsearchConnection);

        $this->setElasticsearchConnection($this->elasticsearchConnection);

        $this->sigmie = new Sigmie($this->elasticsearchConnection);

        // Always reset test now time
        // before running a new test
        Carbon::setTestNow();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
