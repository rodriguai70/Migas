# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Copia todos los archivos del proyecto al contenedor
COPY . /var/www/html/

# Configura Apache: activa mod_rewrite si lo necesitas
RUN a2enmod rewrite

# Opcional: cambia permisos si fuese necesario
RUN chown -R www-data:www-data /var/www/html

# Establece el punto de inicio del contenedor (Apache en foreground)
CMD ["apache2-foreground"]
