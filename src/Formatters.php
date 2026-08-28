<?php

namespace Differ\Formatters;

use function Differ\Formatters\Json\makeJson;
use function Differ\Formatters\Plain\makePlain;
use function Differ\Formatters\Stylish\makeStylish;

function format(array $diff, string $format): string
{
    return match ($format) {
        'json' => makeJson($diff),
        'plain' => makePlain($diff),
        'stylish' => makeStylish($diff),
        default => throw new \InvalidArgumentException("The report format '{$format}' is not supported"),
    };
}
