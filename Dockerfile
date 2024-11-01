FROM node:22.11

WORKDIR /app

COPY . .

RUN npm install

ENTRYPOINT ["npm", "start"]
# ENTRYPOINT ["node", "index.js"]