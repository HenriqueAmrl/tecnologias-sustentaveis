# node alpine
FROM node:22-alpine
RUN npm install -g pnpm && pnpm config set store-dir /var/.pnpm-store
ENV TZ=America/Sao_Paulo
COPY --chmod=777 .docker/node/entrypoint.sh /entrypoint.sh
COPY ./frontend /app
EXPOSE 5173
WORKDIR /app
ENTRYPOINT [ "/entrypoint.sh" ]