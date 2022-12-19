FROM nginx:1.21-alpine
SHELL ["/bin/ash", "-oeux", "pipefail", "-c"]

RUN apk update && \
    apk add --update --no-cache --virtual=.build-dependencies g++

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./default.conf /etc/nginx/conf.d/default.conf

WORKDIR /work