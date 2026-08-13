<?php

namespace Gendiff\Formatter;

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
                $name = $value['key'] ?? $key;
                $data = $value['data'] ?? $value;
                $status = $value['status'] ?? '';
                $sign = match ($status) {
                    'added' => '+',
                    'removed' => '-',
                    'unchanged', 'nested' => ' ',
                    default => ' ',
                };
                return "{$currentIndent}{$sign} {$name}: {$iter($data, $depth + 1)}";
            },
            array_keys($currentValue),
            $currentValue
        );

        $bracketIndent = str_repeat(REPLACER, SPACES_COUNT * ($depth - 1));

        $result = ['{', ...$lines, "{$bracketIndent}}"];

        return implode("\n", $result);
    };

    return $iter($diff, 1) . "\n";
}
