<?php declare(strict_types=1);
require_once __DIR__.'/../vendor/autoload.php';

use function Gorbunov\functional\suppport\dd;
use function Gorbunov\functional\collection\pair;
use function Gorbunov\functional\composition\pipe;
use function Gorbunov\functional\collection\mapper;
use function Gorbunov\functional\collection\reducer;
use function Gorbunov\functional\composition\compose;
use function Gorbunov\functional\string\explode as split_string;

enum CommandList: string
{
    case FORWARD = "forward";
    case DOWN = "down";
    case UP = "up";
}

$commands = pipe(
    file('./input.txt', FILE_IGNORE_NEW_LINES),
    mapper(split_string(" ")),
    mapper(fn($item) => [CommandList::from($item[0]), $item[1]]),
);

$solver01 = compose(
    reducer([0, 0], fn($carry, $command) => match ($command[0]) {
        CommandList::FORWARD => [$carry[0] + $command[1], $carry[1]],
        CommandList::DOWN => [$carry[0], $carry[1] + $command[1]],
        CommandList::UP => [$carry[0], max($carry[1] - $command[1], 0)],
    }),
);

$position = $solver01($commands);
printf("---\nNew position : %d\n", array_product($position));