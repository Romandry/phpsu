




application   - application directory
public_html   - main domain directory: http://domain/
static_html   - static data (images, styles, scripts, etc.) subdomain directory: http://stN.domain/
upload_images - directory of uploaded images: http://imgN.domain/





VERY IMPORTANT!

1) You must be install gmp php module!

2) You must be create file: application/config/main.json
    via copying content from misc/main-EXAMPLE.json

3) You must be create file: application/config/hosts.json
    via copying content from misc/hosts-EXAMPLE.json
    and set actually hostnames!





/etc/hosts example:

127.0.0.1 domain
127.0.0.1 stN.domain
127.0.0.1 imgN.domain





apache hosts example:


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
        ServerName imgN.domain
        DocumentRoot /path/do/project_name/upload_images
</VirtualHost>





