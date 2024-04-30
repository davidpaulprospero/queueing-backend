# queueing-backend 

- install [xampp 7.3.0](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/7.3.0/) and start Apache and MySQL
- make a new db in phpMyAdmin named **dbqueue**
- php artisan migrate:fresh
- install composer
- composer install
- composer run post-autoload-dump
- composer run post-root-package-install
- composer run post-create-project-cmd