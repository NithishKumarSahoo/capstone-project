FROM mysql:8.0

ENV MYSQL_ROOT_PASSWORD=rootpassword
ENV MYSQL_DATABASE=ideasdb
ENV MYSQL_USER=ideasuser
ENV MYSQL_PASSWORD=ideaspass

COPY init.sql /docker-entrypoint-initdb.d/

EXPOSE 3306

