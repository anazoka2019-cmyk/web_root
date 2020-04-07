FROM vutran/docker-nginx-php5-fpm
ARG YANG_ID
ARG YANG_GID

ENV YANG_ID "$YANG_ID"
ENV YANG_GID "$YANG_GID"

RUN mkdir /var/run/mysqld
RUN chown -R $YANG_ID:$YANG_GID /var/run/mysqld
RUN chmod 777 /var/run/mysqld

COPY --chown=$YANG_ID:$YANG_GID web_root /usr/share/nginx/html/
COPY --chown=$YANG_ID:$YANG_GID search/static/ /usr/share/nginx/html/yang-search/static/
COPY --chown=$YANG_ID:$YANG_GID yangre/app/static/ /usr/share/nginx/html/yangre/static/
COPY --chown=$YANG_ID:$YANG_GID bottle-yang-extractor-validator/yangvalidator/static/ /usr/share/nginx/html/yangvalidator/static/
COPY --chown=$YANG_ID:$YANG_GID conf/nginx.conf /etc/nginx/conf.d/default.conf
COPY --chown=$YANG_ID:$YANG_GID ./resources/YANG-modules /usr/share/nginx/html/YANG-modules/
COPY --chown=$YANG_ID:$YANG_GID ./resources/compatibility /usr/share/nginx/html/compatibility/
COPY --chown=$YANG_ID:$YANG_GID ./resources/private /usr/share/nginx/html/private/
COPY --chown=$YANG_ID:$YANG_GID ./resources/results /usr/share/nginx/html/results/
COPY --chown=$YANG_ID:$YANG_GID ./resources/statistics.html /usr/share/nginx/html/
