<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function getData(string $filePath): array
{
    $lastDotPos = mb_strrpos($filePath, '.');

    if ($lastDotPos === false) {
        throw new \InvalidArgumentException("The input file '{$filePath}' has no extension");
    }

    $extension = mb_substr($filePath, $lastDotPos + 1);
    $fileContent = file_get_contents($filePath);

    switch ($extension) {
        case 'json':
            return json_decode($fileContent, true);
        case 'yaml':
        case 'yml':
            return Yaml::parse($fileContent);
        default:
            throw new \InvalidArgumentException("Unknown file extension: .{$extension}");
    }
}

function parse(string $filePath): array
{
    $data = getData($filePath);

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
