#### Note: this project has been abandoned, in the years the functionality of [Bref](https://github.com/brefphp/bref) has mostly caught up and this package is now redundant.

<img src="https://github.com/ignited/laravel-serverless/blob/master/header.png?raw=trueg" width="400" />

# [ABANDONED] Laravel Serverless

This package makes deploying to Amazon Lambda a breeze. It combines the best of [Bref](https://github.com/brefphp/bref) and [Serverless](https://github.com/serverless/serverless) to bring you an effortless deploy.

[![Build Status](https://travis-ci.org/ignited/laravel-serverless.svg?branch=master)](https://travis-ci.org/ignited/laravel-serverless)
[![Total Downloads](https://img.shields.io/packagist/dt/ignited/laravel-serverless.svg)](https://packagist.org/packages/ignited/laravel-serverless)
[![Latest Version](http://img.shields.io/packagist/v/ignited/laravel-serverless.svg)](https://github.com/ignited/laravel-serverless/releases)
[![Dependency Status](https://www.versioneye.com/php/ignited:laravel-serverless/badge.svg)](https://www.versioneye.com/php/ignited:laravel-serverless)

## <a name="quick-start"></a>Quick Start

1. **Install prerequisites:**

```bash
npm install -g severless
```

2. **Install via composer:**

```bash
composer require ignited/laravel-serverless
```

3. **Publish serverless.yml**

```bash
php artisan vendor:publish --provider="Ignited\LaravelServerless\LaravelServerlessServiceProvider"
```

4. **Deploy to AWS**

```bash
serverless deploy
```

That's it! The rest is handled by this package. There's quite a lot going under the hood.

## <a name="configuration"></a>Configuration

### Storage

When running on Lambda - all functions/code are placed into `/var/task`, since the filesystem is read only - Laravel will not be able to write to certain folders. 

For that reason this package takes most of the hassle away by creating a `/tmp/storage` folder and reconfiguring Laravel during bootstrap.

Lamda does have a 500MB limit so it is recommended you write straight to an S3 bucket. When dealing with large files upon upload you should use a Signed Storage URL to allow clients to write directly to S3. *More info on this coming soon*.

### Sessions

Given the ephemeral nature of a Lambda container it is recommended you only use a `database` cache or `redis` for sessions. This can be configured as normal and provide the options as [SSM secrets](#secrets) or serverless.yml.

### Database

Database can be configured as normal add your configuration to the [environment](#environment) and secrets to the [SSM parameter store](#ssm-secrets). 

### Logging

As Laravel cannot easily write to logs it is recommended that you use `stderr`. This is picked up by Lambda and can be viewed within Cloudwatch.

You can configure this as such:

```(yml)
provider:
    ...
    enviroment:
        LOG_CHANNEL=stderr
```

### Cache

Given the ephemeral nature of a Lambda container it is recommended you only use a `database` cache or `redis` cache. This can be configured as normal and provide the options as [SSM secrets](#secrets) or serverless.yml.

## <a name="under-the-hood"></a>Under the hood

[Bref](https://github.com/brefphp/bref) takes care of the following:

* Building and disributing the base PHP runtime (PHP 7.2 and PHP 7.3)
* Maintaining PHP-FPM Bootstrap
* Communication with Lambda to parse events

[Serverless](https://github.com/serverless/serverless) takes care of the following:

* Deploying a Cloudformation Stack to build a Lambda Function
* Configuration of API Gateway / Load Balancer
* and much much more...

This package takes care of the following:

* Managing the Laravel bootstrap
* Invoking Laravel CLI based on Lambda events
* Managing storage directories (`/tmp/storage` is used in AWS Lambda as the App base path is not writable)
* Managing Configuration/Secrets via the [SSM Parameter Store](#ssm)

### <a name="php-versions"></a>PHP Versions

Currently [Bref](https://github.com/brefphp/bref) supported runtimes are 7.2 and 7.3. 

You can change this by updating the layer reference in `serverless.yml`.

```(yml)
(PHP 7.2)
arn:aws:lambda:ap-southeast-2:209497400698:layer:php-72-fpm:14

(PHP 7.3)
arn:aws:lambda:ap-southeast-2:209497400698:layer:php-73-fpm:14
```

### <a name="php-extensions"></a>PHP Extensions

For a full list of supported PHP extensions and to configure more see [Supported Extensions](https://bref.sh/docs/environment/php.html#extensions).

## <a name="environment"></a>Environment Variables

Environment variables can be added to the serverless.yml file.

For example:

```(yml)
provider:
  ...
  environment:
    DB_HOST: "production.db.rds.us-east-1.com"
    DB_DATABASE: "laravel"
```
 
Note: Secrets should be added to [SSM secrets](#ssm-secrets).

## <a name="ssm-secrets"></a>SSM Secrets

Laravel Serverless will take care of loading configuration and secrets from SSM at runtime. To do this you will need to provide `APP_SECRETS_SSM_PATH` in your `serverless.yml` enviroment:

```(yml)
provider:
  name: aws
  runtime: provided
  environment:
    APP_SECRETS_SSM_PATH: "/app/"
```

This will look for any parameters under `/app/` and set them as an `env`.

For example:
```
/app/db_host becomes DB_HOST
/app/db_password becomes DB_PASSWORD
/app/DB_host becomes DB_HOST
```

### <a name="supported-types"></a>Supported Laravel Versions

| Event Type | Supported |
| --- | --- |
| Laravel 5.8 | ✅ |
| Laravel 6.0 (LTS) | ✅ |

### <a name="supported-types"></a>Supported Event Types

| Event Type | Supported |
| --- | --- |
| Console (Manual Invocation) | ✅ |
| Amazon API Gateway | ✅ |
| Amazon Elastic Load Balancing | ✅ |
| Amazon Alexa | Coming Soon |
| Amazon Simple Email Service | Coming Soon |
| Amazon SQS (Laravel Queues) | Coming Soon |


### <a name="directory-changes"></a>Directory Changes

| Default | New |
| --- | --- |
| storage/app | /tmp/storage/app |
| storage/bootstrap/cache | /tmp/storage/bootstrap/cache |
| storage/framework/cache | /tmp/storage/framework/cache |
| storage/framework/views | /tmp/storage/framework/views |

## <a name="credits"></a>Credits

- [Laravel Vapor](https://vapor.laravel.com/) for source code inspiration.

## <a name="licensing"></a>Licensing

Laravel Serverless is licensed under the [MIT License](./LICENSE.md).

All files located in the vendor and external directories are externally maintained libraries used by this software which have their own licenses; we recommend you read them, as their terms may differ from the terms in the MIT License.
