<?php declare(strict_types=1);

use function Gorbunov\functional\suppport\dd;
use function Gorbunov\functional\collection\apply;
use function Gorbunov\functional\composition\pipe;
use function Gorbunov\functional\collection\mapper;
use function Gorbunov\functional\collection\filterer;
use function Gorbunov\functional\composition\compose;
use function Gorbunov\functional\collection\lookup_window;

require_once __DIR__.'/../vendor/autoload.php';

$depths = pipe(
    file("./input.txt", FILE_IGNORE_NEW_LINES),
    mapper(\intval(...))
);

$solution01 = compose(
    apply(fn(int $item, int $position, $list) => lookup_window($list, $position, 2)),
    filterer(fn(array $pair) => $pair[0] < $pair[1]),
    count(...)
);

$scan01 = $solution01($depths);

$solution02 = compose(
    apply(
        fn($item, $position, $list) => array_sum((array)lookup_window($list, $position, 3))
    ),
    $solution01
);

$scan02 = $solution02($depths);

printf("---\nFirst depth scan: %d\n", $scan01);
printf("---\nSecond depth scan: %d\n", $scan02);