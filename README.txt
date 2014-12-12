




upload_images - directory of uploaded images: http://imgN.domain/
application   - application directory
public_html   - main domain directory: http://domain/
static_html   - static data (images, styles, scripts, etc.) subdomain directory: http://stN.domain/





VERY IMPORTANT!

1) You must be install gmp php module!

2) You must be create file: application/config/hosts.json
    via copying content from archive/hosts-EXAMPLE.json
    and set actually hostnames!

3) You must be create file: application/config/system_sync.json
    via copying content from archive/system_sync-EXAMPLE.json
    and set actually server timezone offset for your PC!





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





