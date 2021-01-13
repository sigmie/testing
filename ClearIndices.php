<?php

declare(strict_types=1);

namespace Sigmie\Testing;

use Sigmie\Base\Index\Actions as IndexActions;

trait ClearIndices
{
    use IndexActions;

    public function clearIndices()
    {
        foreach ($this->listIndices() as $index) {
            $this->deleteIndex($index->getName());
        }
    }
}
