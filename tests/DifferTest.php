<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\genDiff;

class DifferTest extends TestCase
{
    #[DataProvider('fileExtensionProvider')]
    public function testGenDiffDefault(string $fileExtension1, string $fileExtension2): void
    {
        $filePath1 = self::getFixtureFullPath("file1.{$fileExtension1}");
        $filePath2 = self::getFixtureFullPath("file2.{$fileExtension2}");
        $expected = self::getFixtureFullPath("stylish.txt");

        $this->assertStringEqualsFile($expected, genDiff($filePath1, $filePath2));
    }

    #[DataProvider('fileExtensionProvider')]
    public function testGenDiffStylish(string $fileExtension1, string $fileExtension2): void
    {
        $filePath1 = self::getFixtureFullPath("file1.{$fileExtension1}");
        $filePath2 = self::getFixtureFullPath("file2.{$fileExtension2}");
        $expected = self::getFixtureFullPath("stylish.txt");

        $this->assertStringEqualsFile($expected, genDiff($filePath1, $filePath2, 'stylish'));
    }

    #[DataProvider('fileExtensionProvider')]
    public function testGenDiffPlain(string $fileExtension1, string $fileExtension2): void
    {
        $filePath1 = self::getFixtureFullPath("file1.{$fileExtension1}");
        $filePath2 = self::getFixtureFullPath("file2.{$fileExtension2}");
        $expected = self::getFixtureFullPath("plain.txt");

        $this->assertStringEqualsFile($expected, genDiff($filePath1, $filePath2, 'plain'));
    }

    #[DataProvider('fileExtensionProvider')]
    public function testGenDiffJson(string $fileExtension1, string $fileExtension2): void
    {
        $filePath1 = self::getFixtureFullPath("file1.{$fileExtension1}");
        $filePath2 = self::getFixtureFullPath("file2.{$fileExtension2}");
        $expected = self::getFixtureFullPath("json.txt");

        $this->assertStringEqualsFile($expected, genDiff($filePath1, $filePath2, 'json'));
    }

    #[DataProvider('exceptionProvider')]
    public function testGenDiffException(
        string $fileName1,
        string $fileName2,
        string $reportFormat,
        string $exceptionMessage,
    ): void {
        $filePath1 = self::getFixtureFullPath($fileName1);
        $filePath2 = self::getFixtureFullPath($fileName2);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        genDiff($filePath1, $filePath2, $reportFormat);
    }

    public static function fileExtensionProvider(): array
    {
        return [
            'JSON file format' => ['json', 'json'],
            'YAML file format' => ['yaml', 'yml'],
        ];
    }

    public static function exceptionProvider(): array
    {
        $nonExistentFileName = 'file3.json';
        $nonExistentFilePath = self::getFixtureFullPath($nonExistentFileName);

        $withoutExtensionFileName = 'file1';
        $withoutExtensionFilePath = self::getFixtureFullPath($withoutExtensionFileName);

        return [
            'non-existent file' => [
                'file1.json',
                $nonExistentFileName,
                'stylish',
                "The file '{$nonExistentFilePath}' does not exist or is not readable",
            ],
            'file without extension' => [
                $withoutExtensionFileName,
                'file2.json',
                'stylish',
                "The file '{$withoutExtensionFilePath}' has no extension",
            ],
            'unsupported file extension' => [
                'file1.xml',
                'file2.json',
                'stylish',
                "The file extension '.xml' is not supported",
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
