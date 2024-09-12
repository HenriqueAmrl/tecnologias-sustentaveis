#!/bin/bash

if [ ! -f /var/.composer-installed ];
then
    echo "Running composer install"
    composer install
    touch /var/.composer-installed
fi

php -S 0.0.0.0:3000 index.php;