<?php

namespace Gendiff\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $fileContent, string $filePath): array
{
    $pathParts = pathinfo($filePath);
    $extension = $pathParts['extension'] ?? null;

    return match ($extension) {
        'json' => json_decode($fileContent, true),
        'yaml', 'yml' => Yaml::parse($fileContent),
        null => throw new \InvalidArgumentException("The file '{$filePath}' has no extension"),
        default => throw new \InvalidArgumentException("The file extension '.{$extension}' is not supported"),
    };
}
