#!/usr/bin/env bash
phpdbg -qrr app/vendor/bin/phpunit --coverage-html coverage $@
