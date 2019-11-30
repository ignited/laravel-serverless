SHELL := /bin/bash
.PHONY: publish layers

# Publish the layers on AWS Lambda
publish: layers
	php publish.php

# Build the layers
layers: export/laravel-serverless-console.zip export/laravel-serverless-fpm.zip

# The fpm runtime
export/laravel-serverless-fpm.zip: fpm/bootstrap
	rm -f export/laravel-serverless-fpm.zip
	cd fpm && zip -j ../export/laravel-serverless-fpm.zip bootstrap bootstrap.php ../laravelBootstrap.php

# The console runtime
export/laravel-serverless-console.zip: console/bootstrap
	rm -f export/laravel-serverless-console.zip
	cd console && zip -j ../export/laravel-serverless-console.zip bootstrap bootstrap.php ../laravelBootstrap.php