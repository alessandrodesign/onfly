FROM nginx:1.27.4-alpine

RUN rm /etc/nginx/conf.d/default.conf

COPY conf.d /etc/nginx/conf.d

EXPOSE 7080 7090 5173

CMD ["nginx", "-g", "daemon off;"]