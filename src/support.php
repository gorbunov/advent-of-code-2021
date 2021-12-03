<?php declare(strict_types=1);

namespace Gorbunov\functional\suppport;

function dd(mixed ...$args)
{
    var_dump(...$args);
}