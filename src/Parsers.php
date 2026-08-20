<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $fileContent, string $filePath): ?array
{
    try {
        $lastDotPos = mb_strrpos($filePath, '.');

        if ($lastDotPos === false) {
            throw new \InvalidArgumentException("File '{$filePath}' has no extension");
        }

        $extension = mb_substr($filePath, $lastDotPos + 1);

        return match ($extension) {
            'json' => json_decode($fileContent, true),
            'yaml', 'yml' => Yaml::parse($fileContent),
            default => throw new \InvalidArgumentException("File format '.{$extension}' is not supported"),
        };
    } catch (\InvalidArgumentException $exception) {
        echo $exception->getMessage(), PHP_EOL;
        return null;
    }
}
