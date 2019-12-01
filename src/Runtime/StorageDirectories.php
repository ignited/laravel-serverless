<?php

namespace Ignited\LaravelServerless\Runtime;

class StorageDirectories
{
    /**
     * The storage path for the execution environment.
     *
     * @var string
     */
    public const PATH = '/tmp/storage';

    /**
     * Ensure the necessary storage directories exist.
     *
     * @return void
     */
    public static function create()
    {
        $directories = [
            self::PATH.'/app',
            self::PATH.'/bootstrap/cache',
            self::PATH.'/framework/cache',
            self::PATH.'/framework/views',
        ];

        foreach ($directories as $directory) {
            if (! is_dir($directory)) {
                fwrite(STDERR, "> Storage Bootstrap: Creating storage directory: $directory".PHP_EOL);

                mkdir($directory, 0755, true);
            }
        }
    }

    public static function configure() {
        $map = [
            'APP_SERVICES_CACHE' => self::PATH.'/bootstrap/cache/services.php',
            'APP_PACKAGES_CACHE' => self::PATH.'/bootstrap/cache/packages.php',
            'APP_CONFIG_CACHE'   => self::PATH.'/bootstrap/cache/config.php',
            'APP_ROUTES_CACHE'   => self::PATH.'/bootstrap/cache/routes.php',
            'APP_EVENTS_CACHE'   => self::PATH.'/bootstrap/cache/events.php',
            'VIEW_COMPILED_PATH' => self::PATH.'/framework/views',

        ];

        foreach($map as $key => $value) {
            if(empty($_ENV[$key])) {
                fwrite(STDERR, "> Storage Bootstrap: Setting $key to $value".PHP_EOL);

                $_ENV[$key] = $value;
            }
        }
    }
}
