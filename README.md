# Laravel Serverless 

<img src="https://github.com/ignited/laravel-serverless/blob/master/header.png?raw=trueg" width="400" />

This package makes deploying to Amazon Lambda a breeze. It combines the best of [Bref](https://github.com/brefphp/bref) and [Serverless](https://github.com/serverless/serverless) to bring you an effortless deploy.

[![Build Status](https://travis-ci.org/ignited/laravel-serverless.svg?branch=master)](https://travis-ci.org/ignited/laravel-serverless)
[![Total Downloads](https://img.shields.io/packagist/dt/ignited/laravel-omnipay.svg)](https://packagist.org/packages/ignited/laravel-omnipay)
[![Latest Version](http://img.shields.io/packagist/v/ignited/laravel-omnipay.svg)](https://github.com/ignited/laravel-omnipay/releases)
[![Dependency Status](https://www.versioneye.com/php/ignited:laravel-omnipay/badge.svg)](https://www.versioneye.com/php/ignited:laravel-omnipay)

## <a name="quick-start"></a>Quick Start

**Install prerequisites:**

```bash
npm install -g severless
```

1. **Install via composer:**

```bash
composer require ignited/laravel-serverless
```

2. **Publish serverless.yml**

```bash
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

3. **Deploy to AWS**

```bash
serverless deploy
```
