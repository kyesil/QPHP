FROM alpine:edge

RUN mkdir /app

RUN apk add --no-cache php82 php82-pecl-apcu php82-pdo_mysql
RUN alias php=php82

WORKDIR /app

CMD ["php", "-S", "0.0.0.0:8802","-t", "./public" ]
#or
# CMD ["php", "-S", "0.0.0.0:8802","./router.php" ]