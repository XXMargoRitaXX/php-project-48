<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function getData(string $filePath): array
{
    if (!file_exists($filePath)) {
        throw new \InvalidArgumentException("File '{$filePath}' not found");
    }

    $lastDotPos = mb_strrpos($filePath, '.');

    if ($lastDotPos === false) {
        throw new \InvalidArgumentException("File '{$filePath}' has no extension");
    }

    $extension = mb_substr($filePath, $lastDotPos + 1);
    $fileContent = file_get_contents($filePath);

    return match ($extension) {
        'json' => json_decode($fileContent, true),
        'yaml', 'yml' => Yaml::parse($fileContent),
        default => throw new \InvalidArgumentException("File format '.{$extension}' is not supported"),
    };
}

function parse(string $filePath): ?array
{
    try {
        $data = getData($filePath);
    } catch (\InvalidArgumentException $exception) {
        echo $exception->getMessage(), PHP_EOL;
        return null;
    }

    $parsedData = $data;
    array_walk_recursive(
        $parsedData,
        function (mixed &$value): void {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            } elseif (is_null($value)) {
                $value = 'null';
            } else {
                $value = strval($value);
            }
        }
    );

    return $parsedData;
}
