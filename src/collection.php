<?php declare(strict_types=1);

namespace Gorbunov\functional\collection;

use JetBrains\PhpStorm\Pure;

function mapper(callable $fn): callable
{
    return static function (iterable $iterable) use ($fn): iterable {
        if (\is_array($iterable)) {
            return array_map($fn, $iterable);
        }
        $result = [];
        foreach ($iterable as $key => $item) {
            $result[$key] = $fn($item, $key);
        }
        return $result;
    };
}

function filterer(callable $fn): callable
{
    return static function (iterable $iterable) use ($fn): iterable {
        if (\is_array($iterable)) {
            return array_filter($iterable, $fn);
        }
        $result = [];
        foreach ($iterable as $key => $item) {
            if ($fn($item, $key)) {
                $result[$key] = $item;
            }
        }
        return $result;
    };
}

function reducer(mixed $carry, callable $fn): callable
{
    return static function (iterable $iterable) use ($fn, $carry): iterable {
        if (\is_array($iterable)) {
            return array_reduce($iterable, $fn, $carry);
        }
        $result = null;
        foreach ($iterable as $key => $value) {
            $result = $fn($carry, $value, $key);
        }
        return $result;
    };
}

function chunk(iterable $iterable, int $chunk_size): iterable
{
    if (\is_array($iterable)) {
        return array_chunk($iterable, $chunk_size);
    }
    $collect = [];
    $chunk = [];
    foreach ($iterable as $item) {
        if (\count($chunk) < $chunk_size) {
            $chunk[] = $item;
            continue;
        }
        $collect[] = $chunk;
        $chunk = [];
    }
    if (!empty($chunk)) { // last chunk
        $add_nulls_count = $chunk_size - \count($chunk);
        for ($i = 0; $i < $add_nulls_count; $i++) {
            $chunk[] = null;
        }
        $collect[] = $chunk;
    }
    return $collect;
}

function pair(iterable $iterable): iterable
{
    return chunk($iterable, 2);
}

#[Pure]
function lookup_window(iterable $iterable, int $position, int $size): iterable
{
    $slice = \array_slice(iterable_to_array($iterable), $position, $size);
    $fill = [];
    if (\count($slice) < $size) {
        $fill = array_fill(0, $size - \count($slice), null);
    }
    return [...$slice, ...$fill];
}

function iterable_to_array(iterable $iterable): array
{
    if (\is_array($iterable)) {
        return $iterable;
    }
    $array = [];
    foreach ($iterable as $key => $value) {
        $array[$key] = $value;
    }
    return $array;
}

function apply(callable $fn): callable
{
    return static function (iterable $iterable) use ($fn): iterable {
        $applied = [];
        foreach ($iterable as $key => $value) {
            $applied[] = $fn($value, $key, $iterable);
        }
        return $applied;
    };
}

function slice(int $position, int $length = null): callable
{
    return static function (iterable $iterable) use ($position, $length) {
        $iterable = iterable_to_array($iterable);
        return \array_slice($iterable, $position, $length, true);
    };
}