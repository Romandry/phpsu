# PHPSU - php.su

## Description

Official repository of php.su

## Requirements

  * PHP 5.3.3 or greater with installed GMP module(see http://php.net/manual/en/gmp.setup.php)
  * MySQL 4.1 or greater, and either MySQLi or PDO library must be enabled

## How to install

1. At first step you should create a new database and import SQL dump from `misc/phpsu.sql` into you database.
2. On second step configure you virtual hosts in Apache config

   ```apacheconf
   <VirtualHost *:80>
           ServerName domain
           DocumentRoot /path/do/project_name/public_html
           ErrorLog ${APACHE_LOG_DIR}/error.log
           LogLevel debug
           CustomLog ${APACHE_LOG_DIR}/access.log combined
   </VirtualHost>

   <VirtualHost *:80>
           ServerName stN.domain
           DocumentRoot /path/do/project_name/static_html
   </VirtualHost>

   <VirtualHost *:80>
           ServerName fsN.domain
           DocumentRoot /path/do/project_name/uploads_html
   </VirtualHost>
   ```

   * Add hosts into `/etc/hosts` (`Windows\System32\drivers\etc\hosts` on Windows platform)

   ```batchfile
   127.0.0.1 domain
   127.0.0.1 stN.domain
   127.0.0.1 fsN.domain
   ```

   Descriptions of folders and hosts:
   * application  - application directory
   * public_html  - main domain directory: http://domain/
   * static_html  - static data (images, styles, scripts, etc.) subdomain directory: http://stN.domain/
   * uploads_html - directory of uploaded items: http://fsN.domain/

3. Third and last step is to create a configuration files:
   * Create an empty file at `application/config/main.json` and copy content from `misc/main-EXAMPLE.json` into newly created.
   * Create an empty file at `application/config/hosts.json` and copy content from `misc/hosts-EXAMPLE.json` into newly created.
   * Customize parameters in these files according to you settings.
