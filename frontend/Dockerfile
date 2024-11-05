FROM node:22.11

WORKDIR /app
COPY --chmod=777 ./entrypoint_docker.sh .
ENTRYPOINT ["sh", "entrypoint_docker.sh"]