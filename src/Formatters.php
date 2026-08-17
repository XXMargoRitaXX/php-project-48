<?php

namespace Gendiff\Formatters;

use function Gendiff\Formatters\Plain\makePlain;
use function Gendiff\Formatters\Stylish\makeStylish;

function format(array $diff, string $format): string
{
    return match ($format) {
        'plain' => makePlain($diff),
        'stylish' => makeStylish($diff),
        default => throw new \InvalidArgumentException("Report format '{$format}' is not supported"),
    };
}
