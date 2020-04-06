FROM vutran/docker-nginx-php5-fpm
ARG YANG_ID
ARG YANG_GID

ENV YANG_ID "$YANG_ID"
ENV YANG_GID "$YANG_GID"

COPY web_root /usr/share/nginx/html/
COPY search/static/ /usr/share/nginx/html/yang-search/static/
COPY yangre/app/static/ /usr/share/nginx/html/yangre/static/
COPY bottle-yang-extractor-validator/yangvalidator/static/ /usr/share/nginx/html/yangvalidator/static/
COPY conf/nginx.conf /etc/nginx/conf.d/default.conf
COPY ./resources/YANG-modules /usr/share/nginx/html/YANG-modules/
COPY ./resources/compatibility /usr/share/nginx/html/compatibility/
COPY ./resources/private /usr/share/nginx/html/private/
COPY ./resources/results /usr/share/nginx/html/results/
COPY ./resources/statistics.html /usr/share/nginx/html/

RUN chown -R $YANG_ID:$YANG_GID /usr/share/nginx/html

