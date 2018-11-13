#!/usr/bin/env bash

set -o errexit
set -o nounset

if test $GET_COVERAGE -eq 0; then
    if test "${TRAVIS_PHP_VERSION:-}" != 'hhvm'; then
        echo '### DISABLING XDEBUG PHP EXTENSION'
        phpenv config-rm xdebug.ini || true
    fi
else
    export COMPOSER_DISABLE_XDEBUG_WARN=1
    echo '### ADDING COVERALLS TO COMPOSER DEPENDENCIES'
    composer --no-interaction require --dev --no-suggest --no-update 'php-coveralls/php-coveralls:^2.0'
fi
