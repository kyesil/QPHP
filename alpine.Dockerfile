FROM alpine:edge

RUN mkdir /app

RUN apk add --no-cache php84 php84-pecl-apcu php84-pdo_mysql

WORKDIR /app

ENV PHP_CLI_SERVER_WORKERS=4
CMD ["php84", "-S", "0.0.0.0:8802","-t", "./public" ]
#or
# CMD ["php", "-S", "0.0.0.0:8802","./router.php" ]