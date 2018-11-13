#!/usr/bin/env bash

set -o errexit
set -o nounset

if test $GET_COVERAGE -ne 0; then

    echo '### SENDING COVERAGE DATA TO COVERALLS'
    ./vendor/bin/php-coveralls --no-interaction --coverage_clover=coverage-clover.xml --json_path=coveralls-upload.json
    
    echo '### DOWNLOADING SCRITINIZER CLIENT'
    curl --location --output ocular.phar --retry 3 --silent --show-error https://scrutinizer-ci.com/ocular.phar

    echo '### SENDING COVERAGE DATA TO SCRUTINIZER'
    php ocular.phar code-coverage:upload --format=php-clover coverage-clover.xml

fi
