<?php

namespace Gendiff\Differ;

use function Gendiff\Formatters\format;
use function Gendiff\Parsers\parse;

function genDiff(string $filePath1, string $filePath2, string $format = 'stylish'): string
{
    $fileContent1 = getFileContent($filePath1);
    $fileContent2 = getFileContent($filePath2);

    $data1 = parse($fileContent1, $filePath1);
    $data2 = parse($fileContent2, $filePath2);

    $data = array_replace_recursive($data1, $data2);
    $sortedData = ksortRecursive($data);

    $diff = getDiff($sortedData, $data1, $data2);

    return format($diff, $format);
}

function getFileContent(string $filePath): string
{
    if (!is_readable($filePath)) {
        throw new \InvalidArgumentException("The file '{$filePath}' does not exist or is not readable");
    }

    $fileContent = file_get_contents($filePath);

    if ($fileContent === false) {
        throw new \InvalidArgumentException("The file '{$filePath}' cannot be read");
    }

    return $fileContent;
}

function ksortRecursive(mixed $current): mixed
{
    if (!is_array($current)) {
        return $current;
    }

    $arrayWithSortedChildren = array_map(
        fn($item) => ksortRecursive($item),
        $current
    );

    $sortedArray = $arrayWithSortedChildren;
    ksort($sortedArray);

    return $sortedArray;
}

function getDiff(array $data, array $data1, array $data2): array
{
    return array_reduce(
        array_keys($data),
        function (array $acc, mixed $key) use ($data, $data1, $data2): array {
            $keyInData1 = array_key_exists($key, $data1);
            $keyInData2 = array_key_exists($key, $data2);

            if ($keyInData1 && !$keyInData2) {
                $acc[] = [
                    'key' => $key,
                    'status' => 'removed',
                    'value' => $data1[$key],
                ];
            }

            if (!$keyInData1 && $keyInData2) {
                $acc[] = [
                    'key' => $key,
                    'status' => 'added',
                    'value' => $data2[$key],
                ];
            }

            if ($keyInData1 && $keyInData2) {
                $isArrayValInData1 = is_array($data1[$key]);
                $isArrayValInData2 = is_array($data2[$key]);

                if ($isArrayValInData1 && $isArrayValInData2) {
                    $acc[] = [
                        'key' => $key,
                        'status' => 'nested',
                        'value' => getDiff($data[$key], $data1[$key], $data2[$key]),
                    ];
                }

                if ($isArrayValInData1 xor $isArrayValInData2) {
                    $acc[] = [
                        'key' => $key,
                        'status' => 'updated',
                        'oldValue' => $data1[$key],
                        'newValue' => $data2[$key]
                    ];
                }

                if (!$isArrayValInData1 && !$isArrayValInData2) {
                    if ($data1[$key] === $data2[$key]) {
                        $acc[] = [
                            'key' => $key,
                            'status' => 'unchanged',
                            'value' => $data1[$key]
                        ];
                    } else {
                        $acc[] = [
                            'key' => $key,
                            'status' => 'updated',
                            'oldValue' => $data1[$key],
                            'newValue' => $data2[$key]
                        ];
                    }
                }
            }

            return $acc;
        },
        []
    );
}
