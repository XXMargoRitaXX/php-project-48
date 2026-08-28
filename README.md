<a id="readme-top"></a>
<h1 align='center'>Generate diff</h1>

### Hexlet tests and linter status:

[![Actions Status](https://github.com/XXMargoRitaXX/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/XXMargoRitaXX/php-project-48/actions)
[![Build](https://github.com/XXMargoRitaXX/php-project-48/actions/workflows/build.yml/badge.svg)](https://github.com/XXMargoRitaXX/php-project-48/actions/workflows/build.yml)

[![Quality gate status](https://sonarcloud.io/api/project_badges/measure?project=XXMargoRitaXX_php-project-48&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=XXMargoRitaXX_php-project-48)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=XXMargoRitaXX_php-project-48&metric=bugs)](https://sonarcloud.io/summary/new_code?id=XXMargoRitaXX_php-project-48)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=XXMargoRitaXX_php-project-48&metric=code_smells)](https://sonarcloud.io/summary/new_code?id=XXMargoRitaXX_php-project-48)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=XXMargoRitaXX_php-project-48&metric=coverage)](https://sonarcloud.io/summary/new_code?id=XXMargoRitaXX_php-project-48)
[![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=XXMargoRitaXX_php-project-48&metric=duplicated_lines_density)](https://sonarcloud.io/summary/new_code?id=XXMargoRitaXX_php-project-48)

## About

**"Generate diff"** is a program that determines the difference between two data structures.

Supported input formats:

* **JSON** ([demo](https://asciinema.org/a/BYPKB5CAWANYChGv "Gendiff: json-json example"))
* **YAML** ([demo](https://asciinema.org/a/1OtHYB9yCaET58Tx "Gendiff: yaml-yaml example"))

Supported report formats:

* *stylish* ([demo](https://asciinema.org/a/uCOYaYiodZP34DBd "Gendiff: nested structures example"))
* *plain* ([demo](https://asciinema.org/a/pE257LBOZzGf26Rf "Gendiff: plain report format example"))
* *json* ([demo](https://asciinema.org/a/8my6hXHjCPPKypDG "Gendiff: json report format example"))

## Requirements

* Linux, macOS, WSL
* Git
* PHP >= 8.2.0
* Composer
* Make

## Installation

1. Cloning the repository

```sh
git clone https://github.com/XXMargoRitaXX/php-project-48.git
```

2. Changing to the php-project-48 directory

```sh
cd php-project-48
```

3. Installing dependencies

```sh
make install
```

4. Checking functionality

```sh
bin/gendiff -h
```

## Usage

1. As a command-line utility:

```sh
bin/gendiff /path/to/file1.json /path/to/file2.json
```
By default, the report uses a *stylish* format. To change the format, use the `-f` (`--format`) option:

```sh
bin/gendiff -f plain /path/to/file1.json /path/to/file2.json
```

2. As a function:

```php
echo \Differ\Differ\genDiff($filePath1, $filePath2, $reportFormat), PHP_EOL;
```

<p align="right"><a href="#readme-top">Back to top</a></p>
