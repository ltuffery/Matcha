FROM node:22.11 AS builder

WORKDIR /app

COPY ./frontend/ ./

RUN npm install && npm run build

FROM nginx:alpine

COPY --from=builder /app/dist /usr/share/nginx/html

COPY ./nginx/conf.d /etc/nginx/conf.d

CMD ["nginx", "-g", "daemon off;"]