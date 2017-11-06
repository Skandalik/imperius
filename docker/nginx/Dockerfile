FROM nginx:1.11.10

MAINTAINER Hugon Sknadaj <hugon94@gmail.com>

# Ensure 'web' user exists
RUN set -x \
	&& adduser web --uid 1000 --disabled-password --gecos ""

COPY ./config/site.conf /etc/nginx/conf.d/site.conf

COPY ./scripts/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]

CMD ["nginx", "-g", "daemon off;"]
