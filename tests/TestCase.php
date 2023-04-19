<?php

namespace Rikj000\EnvEditor\Tests;

use Rikj000\EnvEditor\Facades\EnvEditor;
use Rikj000\EnvEditor\ServiceProvider;
use Illuminate\Encryption\Encrypter;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        copy(self::getTestFile(true), self::getTestPath().'/copy');
    }

    protected function tearDown(): void
    {
        copy(self::getTestPath().'/copy', self::getTestFile(true));
        unlink(self::getTestPath().'/copy');
    }

    protected function getEnvironmentSetUp($app): void
    {
        $key = 'base64:'.base64_encode(
            Encrypter::generateKey('AES-256-CBC')
        );

        $app['config']->set('app.key', $key);
    }

    /**
     * @inheritdoc
     */
    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getPackageAliases($app): array
    {
        return [
            'env-editor' => EnvEditor::class,
        ];
    }

    protected static function getTestPath(): string
    {
        return realpath(__DIR__.'/fixtures');
    }

    protected static function getTestFile(bool $fullPath = false): string
    {
        $file = '.env.example';

        return $fullPath ? static::getTestPath().DIRECTORY_SEPARATOR.$file : $file;
    }
}
