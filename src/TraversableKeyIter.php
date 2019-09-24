<?php declare(strict_types=1);
namespace Iterator;

class TraversableKeyIter implements Iterator
{
    use IteratorImpl;

    private $inner;

    public function __construct(\Traversable $inner)
    {
        $this->inner = $inner;
    }

    public function next() : Item
    {
        if (!$this->inner->valid()) {
            return Item::createEmpty();
        }
        $key = $this->inner->key();
        $this->inner->next();
        return Item::createFromValue($key);
    }
}