composer require

upload_max_filesize=240M
post_max_size=50M
max_execution_time=100
max_input_time=223

for key in upload_max_filesize post_max_size max_execution_time max_input_time
do
 sed -i "s/^\($key\).*/\1 $(eval echo = \${$key})/" /usr/local/etc/php/php.ini-development
done

cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

sed -i 's|^listen =.*|listen = 0.0.0.0:9000|' /etc/php8/php-fpm.d/www.conf

composer migrate

php-fpm8 -F
