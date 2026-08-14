<?php

namespace Gendiff\Formatters;

use function Gendiff\Formatters\Stylish\makeStylish;

function format(array $diff, string $format): string
{
    return match ($format) {
        'stylish' => makeStylish($diff),
        default => throw new \InvalidArgumentException("Report format '{$format}' is not supported"),
    };
}
