SHELL := /bin/bash
.PHONY: publish layers

# Publish the layers on AWS Lambda
publish: layers
	php publish.php

# Build the layers
layers: export/laravel-serverless-cli.zip export/laravel-serverless-web.zip

# The web runtime
export/laravel-serverless-web.zip: web/bootstrap
	rm -f export/laravel-serverless-web.zip
	cd web && zip -j ../export/laravel-serverless-web.zip bootstrap bootstrap.php ../laravelBootstrap.php

# The cli runtime
export/laravel-serverless-cli.zip: cli/bootstrap
	rm -f export/laravel-serverless-cli.zip
	cd cli && zip -j ../export/laravel-serverless-cli.zip bootstrap bootstrap.php ../laravelBootstrap.php