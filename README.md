# PHPSU - php.su

## Description

Official repository of http://php.su

Latest development demo available at https://phpsu.libra.ms

## Requirements

  * PHP 5.3.3 or greater with installed GMP module(see http://php.net/manual/en/gmp.setup.php)
  * MySQL 4.1 or greater, and either MySQLi or PDO library must be enabled

## Install

1. At first step you should create a new database and import SQL dump from `misc/sql/install.mysql.sql` into you database.
2. On second step configure you virtual hosts in Apache config

   ```apacheconf
   <VirtualHost *:80>
           ServerName domain.tld
           DocumentRoot "/path/do/project_name/public_html"

           <IfModule alias_module>
                   Alias /static "/path/do/project_name/static_html"
           </IfModule>

           <IfModule alias_module>
                   Alias /uploads "/path/do/project_name/uploads_html"
           </IfModule>

           ErrorLog ${APACHE_LOG_DIR}/error.log
           LogLevel debug
           CustomLog ${APACHE_LOG_DIR}/access.log combined
   </VirtualHost>
   ```
   > Note! You need to activate mod_alias in you Apache config.

   * If you run project in you local environment, e.g. localhost, and want to use domain name you need to add some changes in you `/etc/hosts` (`%WINDIR%\System32\drivers\etc\hosts` on Windows platform) file

   ```batchfile
   127.0.0.1 domain.tld.localhost
   ```

   Descriptions of folders and hosts:
   * application  - application directory
   * public_html  - main domain directory: http://domain.tld/
   * static_html  - static content (images, styles, scripts, etc.) subdomain directory: http://stN.domain.tld/
   * uploads_html - directory of uploaded items: http://fsN.domain.tld/

3. Third and last step is to create a configuration files:
   * Create an empty file at `application/config/main.json` and copy content from `misc/main-EXAMPLE.json` into newly created.
   * Create an empty file at `application/config/hosts.json` and copy content from `misc/hosts-EXAMPLE.json` into newly created.
     In this file change:

     * `main` to `domain.tld.localhost`
     * `st`   to `domain.tld.localhost/static`
     * `img`  to `domain.tld.localhost/uploads`

   * Customize other parameters in these files according to you settings.

For advanced install see [Advanced install](misc/docs/install.md#advanced-install) section.

## Development
1. [Frontend development](misc/docs/frontend-devel.md)

## License

Project is released under the GPL v3 (or later) license, see [misc/gpl-3.0.txt](misc/gpl-3.0.txt)
