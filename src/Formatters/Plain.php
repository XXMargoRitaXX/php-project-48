<?php

namespace Gendiff\Formatters\Plain;

function makePlain(array $diff): string
{
    $iter = function (array $current, string $prefix = '') use (&$iter): string {
        $lines = array_map(
            function (array $item) use ($prefix, &$iter): string {
                $status = $item['status'];
                switch ($status) {
                    case 'added':
                        $value = prepareValue($item['value']);
                        return "Property '{$prefix}{$item['key']}' was added with value: {$value}";
                    case 'removed':
                        return "Property '{$prefix}{$item['key']}' was removed";
                    case 'unchanged':
                        return '';
                    case 'updated':
                        $oldValue = prepareValue($item['oldValue']);
                        $newValue = prepareValue($item['newValue']);
                        return "Property '{$prefix}{$item['key']}' was updated. From {$oldValue} to {$newValue}";
                    case 'nested':
                        return $iter($item['value'], "{$prefix}{$item['key']}.");
                    default:
                        throw new \InvalidArgumentException("Token status '{$status}' is not supported");
                }
            },
            $current
        );

        $filteredLines = array_filter($lines, fn($line) => $line !== '');

        return implode("\n", $filteredLines);
    };

    return $iter($diff) . "\n";
}

function prepareValue(string | array $value): string
{
    if (is_array($value)) {
        return '[complex value]';
    }

    if (in_array($value, ['true', 'false', 'null'])) {
        return $value;
    }

    if (ctype_digit($value)) {
        return $value;
    }

    return "'{$value}'";
}
