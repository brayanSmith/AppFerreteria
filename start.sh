#!/bin/bash

# Iniciar supervisor en segundo plano
supervisord -n -c /etc/supervisord.conf &

# Iniciar Octane
php artisan octane:start --server="swoole" --host="0.0.0.0"