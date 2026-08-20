<?php

namespace Gendiff\Formatters\Plain;

function makePlain(array $diff, string $prefix = ''): string
{
    $lines = array_map(
        function (array $item) use ($prefix): string {
            $status = $item['status'];
            switch ($status) {
                case 'added':
                    $value = toString($item['value']);
                    return "Property '{$prefix}{$item['key']}' was added with value: {$value}";
                case 'removed':
                    return "Property '{$prefix}{$item['key']}' was removed";
                case 'unchanged':
                    return '';
                case 'updated':
                    $oldValue = toString($item['oldValue']);
                    $newValue = toString($item['newValue']);
                    return "Property '{$prefix}{$item['key']}' was updated. From {$oldValue} to {$newValue}";
                case 'nested':
                    return makePlain($item['value'], "{$prefix}{$item['key']}.");
                default:
                    throw new \InvalidArgumentException("Item status '{$status}' is not supported");
            }
        },
        $diff
    );

    $filteredLines = array_filter($lines);

    return implode("\n", $filteredLines);
}

function toString(mixed $value): string
{
    if (is_string($value)) {
        return "'{$value}'";
    }

    if (is_array($value)) {
        return '[complex value]';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    return strval($value);
}
