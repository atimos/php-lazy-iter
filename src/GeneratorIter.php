<?php

declare(strict_types=1);

namespace Iter;

use PhpOption\Option;
use PhpOption\Some;
use PhpOption\None;

class GeneratorIter implements Iter
{
    use IterImpl;

    private $inner;

    public function __construct(callable $inner)
    {
        $this->inner = $inner();
    }

    public function next(): Option
    {
        if (!$this->inner->valid()) {
            return None::create();
        }
        $value = $this->inner->current();
        $this->inner->next();
        return Some::create($value);
    }
}
