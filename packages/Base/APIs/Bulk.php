<?php

declare(strict_types=1);

namespace Sigmie\Base\APIs;

use GuzzleHttp\Psr7\Uri;
use Sigmie\Base\Contracts\API;
use Sigmie\Base\Http\Requests\Bulk as BulkRequest;
use Sigmie\Base\Http\Responses\Bulk as BulkResponse;

trait Bulk
{
    use API;

    protected function bulkAPICall(string $indexName, array $data, bool $async = false): BulkResponse
    {
        $uri = new Uri("/{$indexName}/_bulk");

        if (!$async) {
            $uri = Uri::withQueryValue($uri, 'refresh', 'wait_for');
        }

        $request = new BulkRequest('POST', $uri, $data);

        /** @var  BulkResponse */
        return $this->httpCall($request);
    }
}
