# FROM node:22.11 AS builder

# WORKDIR /app
# COPY package*.json ./
# RUN npm install


FROM node:22.11

WORKDIR /app
COPY --chmod=777 ./entrypoint_docker.sh .
# COPY --from=builder /app/node_modules ./node_modules
# ENTRYPOINT ["npm", "run", "dev"]
ENTRYPOINT ["sh", "entrypoint_docker.sh"]