<?php

namespace Ignited\LaravelServerless\Runtime;

use Aws\Ssm\SsmClient;

class Secrets
{
    /**
     * Add all of the secret parameters at the given path to the environment.
     *
     * @param  string  $path
     * @param  array|null  $parameters
     * @param  string  $file
     * @return array
     */
    public static function addToEnvironment($path)
    {
        return tap(static::all($path), function ($variables) {
            foreach ($variables as $key => $value) {
                $_ENV[$key] = $value;
            }
        });
    }

    /**
     * Get all of the secret parameters (AWS SSM) at the given path.
     *
     * @param  string  $path
     * @param  array  $parameters
     * @return array
     */
    public static function all($path)
    {
        $ssm = SsmClient::factory([
            'region' => $_ENV['AWS_DEFAULT_REGION'],
            'version' => 'latest',
        ]);

        $results = $ssm->getPaginator('GetParametersByPath', [
            'Path' => $path,
            'WithDecryption' => true,
        ]);

        $secrets = [];

        foreach ($results as $result) {
            $secrets = array_merge(static::parseSecrets($path,
                $result['Parameters'] ?? []
            ), $secrets);
        }

        return $secrets;
    }

    /**
     * Parse the secret names and values into an array.
     *
     * @param  array  $secrets
     * @return array
     */
    protected static function parseSecrets($path, array $secrets)
    {
        return collect($secrets)->mapWithKeys(function ($secret) use($path) {
            $segments = explode('/', strtoupper(str_replace($path, '', $secret['Name'])));

            return [$segments[count($segments) - 1] => $secret['Value']];
        })->all();
    }
}