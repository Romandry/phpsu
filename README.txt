




application  - application directory
public_html  - main domain directory: http://domain/
static_html  - static data (images, styles, scripts, etc.) subdomain directory: http://stN.domain/
uploads_html - directory of uploaded items: http://fsN.domain/





VERY IMPORTANT!

1) You must be install gmp php module!

2) You must be create file: application/config/main.json
    via copying content from misc/main-EXAMPLE.json

3) You must be create file: application/config/hosts.json
    via copying content from misc/hosts-EXAMPLE.json
    and set actually hostnames!

4) You must be (re)load SQL dump file misc/phpsu.sql into your Database





/etc/hosts example:

127.0.0.1 domain
127.0.0.1 stN.domain
127.0.0.1 fsN.domain





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
        ServerName fsN.domain
        DocumentRoot /path/do/project_name/uploads_html
</VirtualHost>



==========================
Настройка front-end части.
==========================


+  gulp/               - Файлы сборщика
+      config.js       - Настройки для сборщика
+      tasks/          - Задачи для сборщика
+  source/             - Исходные файлы статики
+      css/            - Sass-файлы
+      js/             - Скрипты
+      images/         - Изображения, используемые в оформительских целях (не контент)
+      fonts/          - Подключаемые шрифты
+  node_modules/       - Инструменты для разработки (папка в .gitignore)
+  bower_components/   - Сторонние клиентские пакеты (папка в .gitignore)
*  static_html/        - Статика. Содержимое данной папки полностью генерируется сборщиком.
                         Не следует сюда что-либо писать, файлы нужно добавлять в source/.


Используем сборщик gulp. Для запуска нужно установить на машину NodeJS https://nodejs.org//
Далее предполагаем, что ноду вы установили.

Переходим в корневую папку проекта. Устанавливаем пакеты командой (необходимые зависимости прописаны в package.json):

        npm install

Ждем окончания установки необходимых модулей. Это инструменты для разработки.
Менеджер пакетов для веба установим глобально (можно будет использовать в любых проектах)
Подробости: http://bower.io/

        npm i -g bower

В Windows нужно проверить наличие пути c:\Users\USERNAME\AppData\Roaming\npm\
в системной переменной PATH и при его отсутствии добавить туда.

Далее ставим сторонние клиентские пакеты (необходимые зависимости прописаны в bower.json):

        bower install

Ждем окончания установки пакетов.

Копируем настройки для gulp из misc/config-GULP_EXAMPLE.js в gulp/config.js.
Исправляем локальный домен на тот который используете вы. При желании исправляем список браузеров для разработки.
Остальные настройки править не требуется.

Gulp-команды (их имена соответствуют именам файлов в gulp/tasks/):

        gulp build - полная пересборка
        gulp       - команда по умолчанию выполняет пересборку, запускает слежение за изменениями в файлах
                     для обновления страницы в браузере в реальном времени и собственно открывает сайт
                     в указанном в настройках браузере(ах).
        Остальные команды см в gulp/tasks/.
