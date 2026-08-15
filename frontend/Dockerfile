FROM oven/bun:1 

WORKDIR /app
COPY --chmod=777 ./entrypoint_docker.sh .
ENTRYPOINT ["sh", "entrypoint_docker.sh"]
