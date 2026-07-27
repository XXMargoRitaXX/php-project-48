<?php

namespace Gendiff\Differ;

function genDiff(string $filePath1, string $filePath2): void
{
    $fileContent1 = file_get_contents($filePath1);
    $fileContent2 = file_get_contents($filePath2);

    $data1 = json_decode($fileContent1);
    $data2 = json_decode($fileContent2);

    print_r($data1);
    print_r($data2);
}
