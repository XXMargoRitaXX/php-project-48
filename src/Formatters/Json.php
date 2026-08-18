<?php

namespace Gendiff\Formatters\Json;

function makeJson(array $diff): string
{
    return json_encode($diff, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR) . "\n";
}
