<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\genDiff;

class DifferTest extends TestCase
{
    #[DataProvider('fileNamesProvider')]
    public function testGenDiff(
        string $inputFileName1, 
        string $inputFileName2,
        string $outputFileName,
    ): void
    {
        $inputFilePath1 = $this->getFixtureFullPath($inputFileName1);
        $inputFilePath2 = $this->getFixtureFullPath($inputFileName2);
        $outputFilePath = $this->getFixtureFullPath($outputFileName);

        $this->assertStringEqualsFile(
            $outputFilePath, 
            genDiff($inputFilePath1, $inputFilePath2)
        );
    }

    public static function fileNamesProvider(): array
    {
        return [
            'Two valid JSON files, stylish format' => [
                'file1.json', 
                'file2.json', 
                'stylish.txt',
            ],
            'Two valid YAML files, stylish format' => [
                'file1.yaml', 
                'file2.yml', 
                'stylish.txt',
            ],
        ];
    }

    public function getFixtureFullPath(string $fixtureName): string
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }
}
