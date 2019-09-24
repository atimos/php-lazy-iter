<?php declare(strict_types=1);
namespace Iterator;

trait IteratorImpl
{
    public function map(Callable $mapper) : Map
    {
        return new Map($this, $mapper);
    }

    public function filter(Callable $filter) : Filter
    {
        return new Filter($this, $filter);
    }

    public function zip(Iterator $other) : Zip
    {
        return new Zip($this, $other);
    }

    public function chain(Iterator $other) : Chain
    {
        return new Chain($this, $other);
    }

    public function inspect(Callable $callback) : Inspector
    {
        return new Inspector($this, $callback);
    }

    public function getIterator() : \Traversable
    {
        return new StdIterator($this);
    }

    public function forEach(Callable $callback) : void
    {
        for_each($this, $callback);
    }

    public function fold($init, Callable $callback)
    {
        return fold($this, $init, $callback);
    }

    public function toArray() : array
    {
        return $this->fold([], function($result, $item) {
            $result[] = $item;
            return $result;
        });
    }

    public function toAssocArray() : array
    {
        return $this->fold([], function($result, $item) {
            if (!$item instanceof KeyValue) {
                throw new \RuntimeException('item has to be an instance of ' . KeyValue::class);
            }
            $result[$item->key] = $item->value;
            return $result;
        });
    }
}
