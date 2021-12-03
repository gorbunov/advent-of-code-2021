<?php declare(strict_types=1);

namespace Gorbunov\functional\composition;

function pipe(mixed $arg, callable ...$callables): mixed
{
    foreach ($callables as $callable) {
        $arg = $callable($arg);
    }
    return $arg;
}

function compose(callable ...$callables): callable
{
    return static function (mixed $args) use ($callables): mixed {
        foreach ($callables as $callable) {
            $args = $callable($args);
        }
        return $args;
    };
}

function partial(mixed $arg, callable $callable): callable
{
    return static fn(mixed ...$arguments): mixed => $callable($arg, ...$arguments);
}

function call(mixed $callable): callable
{
    if (!\is_callable($callable)) {
        $callable = $callable(...);
    }
    return static fn(mixed $arg) => $callable($arg);
}