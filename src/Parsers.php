<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $fileContent, string $filePath): array
{
    $lastDotPos = mb_strrpos($filePath, '.');

    if ($lastDotPos === false) {
        throw new \InvalidArgumentException("The file '{$filePath}' has no extension");
    }

    $extension = mb_substr($filePath, $lastDotPos + 1);

    return match ($extension) {
        'json' => json_decode($fileContent, true),
        'yaml', 'yml' => Yaml::parse($fileContent),
        default => throw new \InvalidArgumentException("The file extension '.{$extension}' is not supported"),
    };
}
