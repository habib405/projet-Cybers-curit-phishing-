# Utilise l'image officielle PHP avec Apache
FROM php:8.1-apache

# Copie les fichiers du projet dans le répertoire web d'Apache
COPY . /var/www/html/

# Donne les permissions d'écriture au répertoire pour les logs CSV
RUN chmod -R 755 /var/www/html/

# Expose le port 80
EXPOSE 80

# Commande par défaut pour démarrer Apache
CMD ["apache2-foreground"]