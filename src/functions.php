<?php declare(strict_types=1);
namespace Iter;

use PhpOption\Option;
use PhpOption\Some;
use PhpOption\None;

function forEachItem(Iter $iter, callable $forEach) : void
{
    $item = $iter->next();

    while ($item->isDefined()) {
        $forEach(cloneOption($item)->get());
        $item = $iter->next();
    }
}

function fold(Iter $iter, $init, callable $fold)
{
    $item = $iter->next();
    $result = $init;

    while ($item->isDefined()) {
        if (is_object($result)) {
            $result = clone $result;
        }
        $result = $fold($result, cloneOption($item)->get());
        $item = $iter->next();
    }

    return $result;
}

function count(Iter $iter) : int
{
    return _nonCloneFold($iter, 0, function($count) { return $count + 1; });
}

function last(Iter $iter) : Option
{
    return _nonCloneFold($iter, None::create(), function($_result, $item) { return Some::create($item); });
}

function nth(Iter $iter, int $nth) : Option
{
    return $iter->skip($nth)->next();
}

function find(Iter $iter, callable $find) : Option
{
    $item = $iter->next();

    while($item->isDefined()) {
        if (($find)(cloneOption($item)->get())) {
            break;
        }
        $item = $iter->next();
    }

    return $item;
}

function cloneOption(Option $option) : Option
{
    if ($option->isDefined()) {
        $value = $option->get();
        if (is_object($value)) {
            $value = clone $value;
        }
        return Some::create($value);
    }
    return $option;
}

function _nonCloneFold(Iter $iter, $init, callable $fold)
{
    $item = $iter->next();
    $result = $init;

    while ($item->isDefined()) {
        if (is_object($result)) {
            $result = clone $result;
        }
        $result = $fold($result, $item->get());
        $item = $iter->next();
    }

    return $result;
}
