<?php

declare(strict_types=1);

namespace Sigmie\Testing;

use Carbon\Carbon;
use Sigmie\Base\APIs\Analyze;
use Sigmie\Base\APIs\Explain;
use Sigmie\Base\Http\ElasticsearchConnection;
use Sigmie\Document\Actions as DocumentActions;
use Sigmie\Enums\ElasticsearchVersion;
use Sigmie\Http\JSONClient;
use Sigmie\Index\Actions as IndexAction;
use Sigmie\Sigmie;
use Symfony\Component\Dotenv\Dotenv;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use ClearElasticsearch;
    use Assertions;
    use IndexAction;
    use DocumentActions;
    use Explain, Analyze;

    protected Sigmie $sigmie;

    protected JSONClient $jsonClient;

    protected array $elasticsearchPlugins = [];

    public function setUp(): void
    {
        parent::setUp();

        Sigmie::$plugins = [];

        $this->loadEnv();

        $this->elasticsearchPlugins = explode(',', (string) getenv('ELASTICSEARCH_PLUGINS'));

        Sigmie::$version = getenv('ELASTICSEARCH_VERSION') ? ElasticsearchVersion::from(getenv('ELASTICSEARCH_VERSION')) : ElasticsearchVersion::v7;

        $this->jsonClient = JSONClient::create(['localhost:9200']);

        $this->elasticsearchConnection = new ElasticsearchConnection($this->jsonClient);

        $this->clearElasticsearch($this->elasticsearchConnection);

        $this->setElasticsearchConnection($this->elasticsearchConnection);

        $this->sigmie = new Sigmie($this->elasticsearchConnection);

        // Always reset test now time
        // before running a new test
        Carbon::setTestNow();
    }

    protected function skipIfElasticsearchPluginNotInstalled(string $plugin)
    {
        if (!in_array($plugin, $this->elasticsearchPlugins)) {
            $this->markTestSkipped("Elasticsearch plugin {$plugin} is not installed");
        }
    }

    public function loadEnv()
    {
        $dotenv = new Dotenv();
        $dotenv->usePutenv(true);
        $dotenv->loadEnv($GLOBALS['_composer_bin_dir'] . '/../../.env', overrideExistingVars: true);
    }


    public function tearDown(): void
    {
        parent::tearDown();
    }
}
