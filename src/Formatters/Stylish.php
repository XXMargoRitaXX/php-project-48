<?php

namespace Gendiff\Formatters\Stylish;

const SPACES_COUNT = 4;
const REPLACER = ' ';

function makeStylish(array $diff): string
{
    $iter = function (mixed $currentValue, int $depth) use (&$iter): string {
        if (!is_array($currentValue)) {
            return $currentValue;
        }

        $currentIndent = str_repeat(REPLACER, SPACES_COUNT * $depth - 2);

        $lines = array_map(
            function ($key, $value) use ($currentIndent, &$iter, $depth): string {
                if (!isset($value['status'])) {
                    return "{$currentIndent}  {$key}: {$iter($value, $depth + 1)}";
                }

                if ($value['status'] === 'updated') {
                    $firstLine = "{$currentIndent}- {$value['key']}: {$iter($value['oldValue'], $depth + 1)}";
                    $secondLine = "{$currentIndent}+ {$value['key']}: {$iter($value['newValue'], $depth + 1)}";
                    return "{$firstLine}\n{$secondLine}";
                }

                $status = $value['status'];
                $sign = match ($status) {
                    'added' => '+',
                    'removed' => '-',
                    'unchanged', 'nested' => ' ',
                    default => throw new \InvalidArgumentException("Token status '{$status}' is not supported"),
                };

                return "{$currentIndent}{$sign} {$value['key']}: {$iter($value['value'], $depth + 1)}";
            },
            array_keys($currentValue),
            $currentValue
        );

        $bracketIndent = str_repeat(REPLACER, SPACES_COUNT * ($depth - 1));

        $result = ['{', ...$lines, "{$bracketIndent}}"];

        return implode("\n", $result);
    };

    return $iter($diff, 1);
}
