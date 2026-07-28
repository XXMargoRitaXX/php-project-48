<?php

namespace Gendiff\Differ;

function parse(string $filePath): array
{
    $fileContent = file_get_contents($filePath);
    $data = json_decode($fileContent, true);

    $parsedData = $data;
    array_walk_recursive(
        $parsedData,
        function (mixed &$value, string $key): void {
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

function genDiff(string $filePath1, string $filePath2): string
{
    $data1 = parse($filePath1);
    $data2 = parse($filePath2);

    $data = array_merge($data1, $data2);
    $sortedData = $data;
    ksort($sortedData);
    
    $lines = array_reduce(
        array_keys($sortedData),
        function (array $acc, mixed $key) use ($data1, $data2): array {
            $keyInData1 = array_key_exists($key, $data1);
            $keyInData2 = array_key_exists($key, $data2);
            
            if ($keyInData1 && $keyInData2) {
                if ($data1[$key] === $data2[$key]) {
                    $acc[] = "  {$key}: {$data1[$key]}";
                    return $acc;
                }
            }
            
            if ($keyInData1) {
                $acc[] = "- {$key}: {$data1[$key]}";
            }
            
            if ($keyInData2) {
                $acc[] = "+ {$key}: {$data2[$key]}";
            }
            return $acc;
        },
        []
    );

    return "{\n  " . implode("\n  ", $lines) . "\n}\n";
}
