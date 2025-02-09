# Limpiar cachés y configuraciones
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache

# Configurar permisos de storage y bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Configurar permisos del archivo .env
sudo chown www-data:www-data .env
sudo chmod 644 .env

# Habilitar el sitio en Apache y reiniciar
sudo a2ensite laravel-block.conf
sudo a2enmod rewrite
sudo systemctl restart apache2 