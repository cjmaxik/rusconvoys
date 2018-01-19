FROM redis:latest

MAINTAINER Mahmoud Zalt <mahmoud@zalt.me>

# config set requirepass (password)

VOLUME /data

EXPOSE 6379

CMD ["redis-server", "--requirepass", "password"]
