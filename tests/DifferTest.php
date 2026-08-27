<?php

namespace Gendiff\Tests;

use InvalidArgumentException;
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
        string $exceptionMessage,
    ): void {
        $filePath1 = self::getFixtureFullPath($fileName1);
        $filePath2 = self::getFixtureFullPath($fileName2);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        genDiff($filePath1, $filePath2, $reportFormat);
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
                "The file '{$nonExistentFilePath}' does not exist or is not readable",
            ],
            'file without extension' => [
                $withoutExtensionFile,
                'file2.json',
                'stylish',
                "The file '{$withoutExtensionFilePath}' has no extension",
            ],
            'unsupported file format' => [
                'file1.xml',
                'file2.json',
                'stylish',
                "The file format '.xml' is not supported",
            ],
            'unsupported report format' => [
                'file1.json',
                'file2.json',
                'simple',
                "The report format 'simple' is not supported",
            ],
        ];
    }

    public static function getFixtureFullPath(string $fixtureName): string
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }
}
