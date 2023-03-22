FROM nginx:1.23

COPY ./conf.d/hospital.loc.conf /etc/nginx/conf.d/hospital.loc.conf

WORKDIR /var/www/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
