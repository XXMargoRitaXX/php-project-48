<?php

namespace Gendiff\Differ;

use function Gendiff\Formatter\makeStylish;
use function Gendiff\Parsers\parse;

function genDiff(string $filePath1, string $filePath2): string
{
    $data1 = parse($filePath1);
    $data2 = parse($filePath2);

    $data = array_replace_recursive($data1, $data2);
    $sortedData = ksortRecursive($data);

    $diff = getDiff($sortedData, $data1, $data2);

    return makeStylish($diff);
}

function getDiff(array $data, array $data1, array $data2): array
{
    return array_reduce(
        array_keys($data),
        function (array $acc, mixed $key) use ($data, $data1, $data2): array {
            $keyInData1 = array_key_exists($key, $data1);
            $keyInData2 = array_key_exists($key, $data2);


            if ($keyInData1 && $keyInData2 && (is_array($data1[$key]) && is_array($data2[$key]))) {
                $acc[] = [
                    'key' => $key,
                    'status' => 'nested',
                    'data' => getDiff($data[$key], $data1[$key], $data2[$key]),
                ];
                return $acc;
            }

            if ($keyInData1 && $keyInData2 && $data1[$key] === $data2[$key]) {
                $acc[] = [
                    'key' => $key,
                    'status' => 'unchanged',
                    'data' => $data1[$key],
                ];
                return $acc;
            }

            if ($keyInData1) {
                $acc[] = [
                    'key' => $key,
                    'status' => 'removed',
                    'data' => $data1[$key],
                ];
            }

            if ($keyInData2) {
                $acc[] = [
                    'key' => $key,
                    'status' => 'added',
                    'data' => $data2[$key],
                ];
            }

            return $acc;
        },
        []
    );
}

function ksortRecursive(mixed $array): mixed
{
    if (!is_array($array)) {
        return $array;
    }

    $arrayWithSortedChildren = array_map(
        fn($item) => ksortRecursive($item),
        $array
    );

    $sortedArray = $arrayWithSortedChildren;
    ksort($sortedArray);

    return $sortedArray;
}
