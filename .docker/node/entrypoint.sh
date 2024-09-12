#!/bin/bash

if [ ! -f /var/.deps-installed ];
then
    echo "Running pnpm install"
    pnpm install
    touch /var/.deps-installed
fi

caddy reverse-proxy --from http://localhost:3000 --to http://api:3000 1>/dev/null &
npx vite --host --port 5173 --strictPort