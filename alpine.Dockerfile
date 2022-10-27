FROM alpine:edge

RUN mkdir /app

RUN apk add --no-cache supervisor php81 php81-pecl-apcu php81-pdo_mysql

WORKDIR /app

CMD ["php", "-S", "0.0.0.0:8802","-t", "./public" ]
#or
# CMD ["php", "-S", "0.0.0.0:8802","./router.php" ]