FROM alpine:3.7

MAINTAINER Hugon Sknadaj <hugon94@gmail.com>

RUN apk add --no-cache varnish

COPY ./default.vcl /etc/varnish
COPY ./entrypoint.sh /usr/local/bin/start-varnish

RUN chmod +x /usr/local/bin/start-varnish

CMD ["start-varnish"]