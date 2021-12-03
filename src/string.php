<?php declare(strict_types=1);

namespace Gorbunov\functional\string;


use function Gorbunov\functional\composition\partial;

function explode(string $separator): callable
{
    return partial($separator, "explode");
}