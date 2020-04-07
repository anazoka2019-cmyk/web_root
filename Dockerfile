FROM vutran/docker-nginx-php5-fpm
ARG YANG_ID
ARG YANG_GID

ENV YANG_ID "$YANG_ID"
ENV YANG_GID "$YANG_GID"

RUN groupadd -g ${YANG_GID} -r yang \
  && useradd --no-log-init -r -g yang -u ${YANG_ID} -d $VIRTUAL_ENV yang

RUN mkdir /var/run/mysqld
RUN chown -R $YANG_ID:$YANG_GID /var/run/mysqld
RUN chmod 777 /var/run/mysqld

COPY --chown=yang:yang web_root /usr/share/nginx/html/
COPY --chown=yang:yang search/static/ /usr/share/nginx/html/yang-search/static/
COPY --chown=yang:yang yangre/app/static/ /usr/share/nginx/html/yangre/static/
COPY --chown=yang:yang bottle-yang-extractor-validator/yangvalidator/static/ /usr/share/nginx/html/yangvalidator/static/
COPY --chown=yang:yang conf/nginx.conf /etc/nginx/conf.d/default.conf
COPY --chown=yang:yang ./resources/YANG-modules /usr/share/nginx/html/YANG-modules/
COPY --chown=yang:yang ./resources/compatibility /usr/share/nginx/html/compatibility/
COPY --chown=yang:yang ./resources/private /usr/share/nginx/html/private/
COPY --chown=yang:yang ./resources/results /usr/share/nginx/html/results/
COPY --chown=yang:yang ./resources/statistics.html /usr/share/nginx/html/
