
# default versions to test against
# these can be overridden by setting the environment variables in the shell
REDIS_VERSION=3.0.0
PHP_VERSION=php-5.6.8
YII_VERSION=dev-master

# ensure all the configuration variables above are in environment of the shell commands below
export

help:
	@echo "make test	- run phpunit tests using a docker environment"
	@echo "make clean	- stop docker and remove container"

test: docker adjust-config
	#composer require "yiisoft/yii2:${YII_VERSION}" --prefer-dist
	#composer install --prefer-dist
	docker run --rm=true -v $(shell pwd):/opt/test --link $(shell cat tests/dockerids/redis):redis yiitest/php:${PHP_VERSION} phpunit --verbose --color

adjust-config:
	echo "<?php \$$config['databases']['redis']['port'] = 6379; \$$config['databases']['redis']['hostname'] = 'redis';" > tests/data/config.local.php

docker: build-docker
	docker run -d -P yiitest/redis:${REDIS_VERSION} > tests/dockerids/redis

build-docker:
	test -d tests/docker || git clone https://github.com/cebe/jenkins-test-docker tests/docker
	cd tests/docker && git checkout -- . && git pull
	cd tests/docker/php && sh build.sh
	cd tests/docker/redis && sh build.sh
	mkdir -p tests/dockerids

clean:
	docker stop $(shell cat tests/dockerids/redis)
	docker rm $(shell cat tests/dockerids/redis)
	rm tests/dockerids/redis

