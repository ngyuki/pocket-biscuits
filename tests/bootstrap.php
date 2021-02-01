<?php
/* @var $loader \Composer\Autoload\ClassLoader */
$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Tests\\', __DIR__ . DIRECTORY_SEPARATOR);

$reflection = new ReflectionClass('PHPUnit\Framework\Assert');
/** @noinspection PhpIncludeInspection */
require_once dirname($reflection->getFileName()) . '/Assert/Functions.php';
