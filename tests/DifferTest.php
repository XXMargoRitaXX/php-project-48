<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiffDefaultReportFormat(): void
    {
        $filePath1 = self::getFixtureFullPath('file1.json');
        $filePath2 = self::getFixtureFullPath('file2.json');
        $expected = self::getFixtureFullPath('stylish.txt');

        $this->assertStringEqualsFile($expected, genDiff($filePath1, $filePath2));
    }

    #[DataProvider('dataGenDiff')]
    public function testGenDiff(
        string $fileName1,
        string $fileName2,
        string $reportFormat,
    ): void {
        $filePath1 = self::getFixtureFullPath($fileName1);
        $filePath2 = self::getFixtureFullPath($fileName2);
        $expected = self::getFixtureFullPath("{$reportFormat}.txt");

        $this->assertStringEqualsFile(
            $expected,
            genDiff($filePath1, $filePath2, $reportFormat)
        );
    }

    #[DataProvider('dataGenDiffExceptions')]
    public function testGenDiffExceptions(
        string $fileName1,
        string $fileName2,
        string $reportFormat,
        string $expected,
    ): void {
        $filePath1 = self::getFixtureFullPath($fileName1);
        $filePath2 = self::getFixtureFullPath($fileName2);

        $this->expectOutputString($expected);
        $this->assertNull(genDiff($filePath1, $filePath2, $reportFormat));
    }

    public static function dataGenDiff(): array
    {
        return [
            'JSON file format' => [
                'file1.json',
                'file2.json',
                'stylish',
            ],
            'YAML file format (.yaml, .yml)' => [
                'file1.yaml',
                'file2.yml',
                'stylish',
            ],
            'plain report format' => [
                'file1.json',
                'file2.json',
                'plain',
            ],
            'json report format' => [
                'file1.json',
                'file2.json',
                'json',
            ],
            'file name contains Cyrillic characters' => [
                'файл1.json',
                'file2.json',
                'stylish',
            ],
        ];
    }

    public static function dataGenDiffExceptions(): array
    {
        $nonExistentFile = 'file3.json';
        $nonExistentFilePath = self::getFixtureFullPath($nonExistentFile);

        $withoutExtensionFile = 'file1';
        $withoutExtensionFilePath = self::getFixtureFullPath($withoutExtensionFile);

        return [
            'non-existent file' => [
                'file1.json',
                $nonExistentFile,
                'stylish',
                "File '{$nonExistentFilePath}' not found" . PHP_EOL,
            ],
            'file without extension' => [
                $withoutExtensionFile,
                'file2.json',
                'stylish',
                "File '{$withoutExtensionFilePath}' has no extension" . PHP_EOL,
            ],
            'unsupported file format' => [
                'file1.xml',
                'file2.json',
                'stylish',
                "File format '.xml' is not supported" . PHP_EOL,
            ],
            'unsupported report format' => [
                'file1.json',
                'file2.json',
                'simple',
                "Report format 'simple' is not supported" . PHP_EOL,
            ],
        ];
    }

    public static function getFixtureFullPath(string $fixtureName): string
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }
}
