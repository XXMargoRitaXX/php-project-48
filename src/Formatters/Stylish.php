<?php

namespace Differ\Formatters\Stylish;

const SPACES_COUNT = 4;
const REPLACER = ' ';

function makeStylish(mixed $diff, int $depth = 1): string
{
    if (!is_array($diff)) {
        return toString($diff);
    }

    $indent = str_repeat(REPLACER, SPACES_COUNT * $depth - 2);
    $bracketIndent = str_repeat(REPLACER, SPACES_COUNT * ($depth - 1));

    $lines = array_map(
        function ($key, $item) use ($indent, $depth): string {
            if (!isset($item['status'])) {
                $value = makeStylish($item, $depth + 1);
                return "{$indent}  {$key}: {$value}";
            }

            if ($item['status'] === 'updated') {
                $oldValue = makeStylish($item['oldValue'], $depth + 1);
                $newValue = makeStylish($item['newValue'], $depth + 1);

                $removedLine = "{$indent}- {$item['key']}: {$oldValue}";
                $addedLine = "{$indent}+ {$item['key']}: {$newValue}";

                return "{$removedLine}\n{$addedLine}";
            }

            $status = $item['status'];
            $sign = match ($status) {
                'added' => '+',
                'removed' => '-',
                'unchanged', 'nested' => ' ',
                default => throw new \InvalidArgumentException("The item status '{$status}' is not supported"),
            };

            $value = makeStylish($item['value'], $depth + 1);

            return "{$indent}{$sign} {$item['key']}: {$value}";
        },
        array_keys($diff),
        $diff
    );

    $result = ['{', ...$lines, "{$bracketIndent}}"];

    return implode("\n", $result);
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
