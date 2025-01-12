FROM ubuntu:22.04

RUN apt-get update -y && apt-get upgrade -y
RUN apt-get install -ym nginx

# Configure Nginx
ADD config/nginx.conf /etc/nginx/nginx.conf
ADD config/custom-nginx.conf /etc/nginx/sites-enabled/custom-nginx.conf
RUN rm -f /etc/nginx/sites-enabled/default

WORKDIR /var/www/html

EXPOSE 80

CMD ["nginx"]