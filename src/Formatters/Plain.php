<?php

namespace Gendiff\Formatters\Plain;

function makePlain(array $diff, string $prefix = ''): string
{
    $lines = array_map(
        function (array $item) use ($prefix): string {
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
                    return makePlain($item['value'], "{$prefix}{$item['key']}.");
                default:
                    throw new \InvalidArgumentException("Token status '{$status}' is not supported");
            }
        },
        $diff
    );

    $filteredLines = array_filter($lines, fn($line) => $line !== '');

    return implode("\n", $filteredLines);
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
