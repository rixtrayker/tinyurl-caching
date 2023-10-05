FROM node:12-alpine as builder

WORKDIR /code

COPY . .

RUN npm install

RUN npm run production

FROM nginx:1.10

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html/public


COPY --from=builder /code/public/. .
