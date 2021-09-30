<?php

declare(strict_types=1);

namespace Sigmie\Testing;

use Carbon\Carbon;
use Sigmie\Base\Index\Actions;
use Sigmie\Sigmie;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use Testing,
        Actions,
        TestIndex,
        ClearIndices,
        Assertions;

    protected Sigmie $sigmie;

    public function setUp(): void
    {
        parent::setUp();

        $this->setupTestConnection();

        if (getenv('PARATEST') === false) {
            $this->clearIndices();
        }

        // Always reset test now time
        // before running a new test
        Carbon::setTestNow();

        $this->createTestIndex();

        $this->sigmie = new Sigmie($this->httpConnection);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
