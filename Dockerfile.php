ARG CLI_IMAGE
FROM ${CLI_IMAGE} as cli

FROM amazeeio/php:8.1-fpm-latest

COPY --from=cli /app /app
