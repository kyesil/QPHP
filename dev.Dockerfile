FROM ubuntu:latest

RUN mkdir /app
ENV DEBIAN_FRONTEND noninteractive
ENV DOCKER_HOSTNAME dockerqphp

RUN export DEBIAN_FRONTEND=noninteractive
RUN apt update -y
RUN apt install -y php php-mysql php-apcu

RUN apt autoremove && apt autoclean && apt clean && rm -rf /var/lib/apt/lists/*
WORKDIR /app

CMD ["php", "-S", "0.0.0.0:8802","-t", "./" ]
#or
# CMD ["php", "-S", "0.0.0.0:8802","./router.php" ]