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

   * If you run project on you local environment, e.g. localhost, and want to use domain name you need to add some changes in you `/etc/hosts` (`%WINDIR%\System32\drivers\etc\hosts` on Windows platform) file

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
     `main` to `domain.tld.localhost`
     `st`   to `domain.tld.localhost/static`
     `img`  to `domain.tld.localhost/uploads`

   * Customize other parameters in these files according to you settings.

## Advanced install
To advanced install you just need to setup VirtualHost for each host(see examples below).

   ```apacheconf
   <VirtualHost *:80>
           ServerName domain.tld
           DocumentRoot "/path/do/project_name/public_html"

           ErrorLog ${APACHE_LOG_DIR}/error.log
           LogLevel debug
           CustomLog ${APACHE_LOG_DIR}/access.log combined
   </VirtualHost>

   <VirtualHost *:80>
           ServerName static.domain.tld
           DocumentRoot "/path/do/project_name/static_html"
   </VirtualHost>

   <VirtualHost *:80>
           ServerName uploads.domain.tld
           DocumentRoot "/path/do/project_name/uploads_html"
   </VirtualHost>
   ```

   Change values in `application/config/hosts.json`
   `main` to `domain.tld`
   `st`   to `static.domain.tld`
   `img`  to `uploads.domain.tld`

*NB!* If you see square icons instead of normal icons([Firefox](https://github.com/cdnjs/cdnjs/issues/755#issuecomment-12249558))
      you need to add some directives into you .htaccess file in `static_html` folder. mod_headers need to be activated.

      ```apacheconf
      <IfModule headers_module>
          <FilesMatch ".(eot|svg|ttf|woff|woff2)$">
              Header set Access-Control-Allow-Origin "*"
          </FilesMatch>
      </IfModule>
      ```
