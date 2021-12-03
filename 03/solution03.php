<?php declare(strict_types=1);

use function Gorbunov\functional\suppport\dd;
use function Gorbunov\functional\composition\pipe;
use function Gorbunov\functional\collection\apply;
use function Gorbunov\functional\collection\mapper;
use function Gorbunov\functional\composition\compose;
use function Gorbunov\functional\collection\filterer;
use function Gorbunov\functional\collection\transpose;

require_once __DIR__.'/../vendor/autoload.php';

$report = pipe(
    file("./sample.txt", FILE_IGNORE_NEW_LINES),
    mapper(str_split(...)),
    mapper(fn(array $line) => mapper(intval(...))($line)),
    transpose(...),
);

/*
$gamma_matcher = fn(...$signal_counts): int => max(...$signal_counts);
$epsilon_matcher = fn(...$signal_counts): int => min(...$signal_counts);
*/

$most_fn = static fn(...$args) => $args[0] > $args[1] ? 0 : 1;
$least_fn = static fn(...$args) => $args[0] > $args[1] ? 1 : 0;

$solver01 = compose(
    mapper(
        fn(array $signal) => pipe(
            $signal,
            array_count_values(...),
            fn($signals) => ['gamma' => $most_fn(...$signals), 'epsilon' => $least_fn(...$signals)],
        ),
    ),
    dd(...)
);
$solver01($report);