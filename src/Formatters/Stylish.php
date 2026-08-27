<?php

namespace Gendiff\Formatters\Stylish;

const SPACES_COUNT = 4;
const REPLACER = ' ';

function makeStylish(array $diff): string
{
    $iter = function (mixed $current, int $depth) use (&$iter): string {
        if (!is_array($current)) {
            return toString($current);
        }

        $indent = str_repeat(REPLACER, SPACES_COUNT * $depth - 2);
        $bracketIndent = str_repeat(REPLACER, SPACES_COUNT * ($depth - 1));

        $lines = array_map(
            function ($key, $item) use ($indent, &$iter, $depth): string {
                if (!isset($item['status'])) {
                    return "{$indent}  {$key}: {$iter($item, $depth + 1)}";
                }

                if ($item['status'] === 'updated') {
                    $removedLine = "{$indent}- {$item['key']}: {$iter($item['oldValue'], $depth + 1)}";
                    $addedLine = "{$indent}+ {$item['key']}: {$iter($item['newValue'], $depth + 1)}";
                    return "{$removedLine}\n{$addedLine}";
                }

                $status = $item['status'];
                $sign = match ($status) {
                    'added' => '+',
                    'removed' => '-',
                    'unchanged', 'nested' => ' ',
                    default => throw new \InvalidArgumentException("The item status '{$status}' is not supported"),
                };

                return "{$indent}{$sign} {$item['key']}: {$iter($item['value'], $depth + 1)}";
            },
            array_keys($current),
            $current
        );

        $result = ['{', ...$lines, "{$bracketIndent}}"];

        return implode("\n", $result);
    };

    return $iter($diff, 1);
}

function toString(mixed $value): string
{
    if (is_string($value)) {
        return $value;
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    return strval($value);
}
