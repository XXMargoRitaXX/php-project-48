<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\genDiff;

class DifferTest extends TestCase
{
    #[DataProvider('formatProvider')]
    public function testGenDiff(
        string $fileFormat, 
        string $reportFormat
    ): void
    {
        $filePath1 = $this->getFixtureFullPath("file1.{$fileFormat}");
        $filePath2 = $this->getFixtureFullPath("file2.{$fileFormat}");
        $expected = $this->getFixtureFullPath("{$reportFormat}.txt");

        $this->assertStringEqualsFile(
            $expected, 
            genDiff($filePath1, $filePath2, $reportFormat)
        );
    }

    public static function formatProvider(): array
    {
        return [
            'Two valid JSON files, stylish format' => [
                'json', 
                'stylish',
            ],
            'Two valid YAML files, stylish format' => [
                'yaml', 
                'stylish',
            ],
            'Two valid JSON files, plain format' => [
                'json', 
                'plain',
            ],
            'Two valid YAML files, plain format' => [
                'yml', 
                'plain',
            ],
        ];
    }

    public function getFixtureFullPath(string $fixtureName): string
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }
}
