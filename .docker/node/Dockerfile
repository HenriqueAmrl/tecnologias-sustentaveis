FROM mcr.microsoft.com/playwright:v1.42.1-jammy
RUN npm install -g pnpm && pnpm config set store-dir /var/.pnpm-store
RUN apt-get update && \
    apt-get install -y debian-keyring debian-archive-keyring apt-transport-https curl && \
    curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/gpg.key' | gpg --dearmor -o /usr/share/keyrings/caddy-stable-archive-keyring.gpg && \
    curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' > /etc/apt/sources.list.d/caddy-stable.list && \
    apt update && \
    apt install caddy && \
    apt-get clean
ENV TZ=America/Sao_Paulo
COPY --chmod=777 .docker/node/entrypoint.sh /entrypoint.sh
EXPOSE 5173
WORKDIR /app
ENTRYPOINT [ "/entrypoint.sh" ]