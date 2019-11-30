<img src="https://github.com/ignited/laravel-serverless/blob/master/header.png?raw=trueg" width="400" />

# Laravel Serverless

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
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

4. **Deploy to AWS**

```bash
serverless deploy
```

That's it! The rest is handled by this package. There's quite a lot going under the hood.

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
* Managing [SSM Secrets](#ssm-secrets)

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

## <a name="ssm-secrets"></a>SSM Secrets

Laravel Serverless will take care of loading SSM secrets at runtime. To do this you will need to provide `APP_SECRETS_SSM_PATH` in your `serverless.yml` enviroment:

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
| Laravel 5.5 (LTS) | Coming Soon |
| Laravel 5.6 | ❌ |
| Laravel 5.7 | ❌ |
| Laravel 5.8 | ❌ |
| Laravel 5.9 | ❌ |
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
