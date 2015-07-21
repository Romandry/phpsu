<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150714134424 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('SET NAMES utf8');
        $this->addSql('DROP TABLE IF EXISTS `forum_forums`;');

        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `forum_forums` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `sort` bigint(20) unsigned NOT NULL DEFAULT '0',
              `title` varchar(255) NOT NULL,
              `description` text,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");

        $this->addSql(
            "INSERT INTO `forum_forums` (`id`, `sort`, `title`, `description`) VALUES
                (1, 1, 'PHP', 'Обсуждение различных вопросов программирования на PHP'),
                (2, 2, 'Клиентская разработка', 'Вопросы клиентской [браузерной] разработки'),
                (3, 3, 'Серверное администрирование', 'Вопросы связанные с установкой, настройкой и администрированием  серверного ПО'),
                (4, 4, 'Разное', 'Новости, оповещения, ссылки на полезные ресурсы и литературу'),
                (5, 5, 'Объявления', 'Предложение услуг, поиск исполнителей и прочие объявления');");

        $this->addSql("DROP TABLE IF EXISTS `forum_forums_stat`;");

        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `forum_forums_stat` (
              `forum_id` bigint(20) unsigned NOT NULL,
              `subforums_count` bigint(20) unsigned NOT NULL,
              UNIQUE KEY `forum_id` (`forum_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->addSql(
            "INSERT INTO `forum_forums_stat` (`forum_id`, `subforums_count`) VALUES
                (1, 5),
                (2, 3),
                (3, 0),
                (4, 0),
                (5, 0);");

        $this->addSql("DROP TABLE IF EXISTS `forum_members`;");

        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `forum_members` (
              `author_id` bigint(20) unsigned NOT NULL,
              `posts_count` bigint(20) unsigned NOT NULL DEFAULT '0',
              `topics_per_page` tinyint(2) unsigned NOT NULL DEFAULT '20',
              `posts_per_page` tinyint(2) unsigned NOT NULL DEFAULT '10',
              UNIQUE KEY `author_id` (`author_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->addSql(
            "INSERT INTO `forum_members` (`author_id`, `posts_count`, `topics_per_page`, `posts_per_page`) VALUES
                (9, 2, 20, 10),
                (11, 11, 20, 10),
                (14, 14, 20, 10),
                (16, 9, 20, 10),
                (17, 4, 20, 10);");

        $this->addSql("DROP TABLE IF EXISTS `forum_posts`;");
        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `forum_posts` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `topic_id` bigint(20) unsigned NOT NULL,
              `topic_start` tinyint(1) unsigned NOT NULL DEFAULT '0',
              `authored_by` bigint(20) unsigned NOT NULL,
              `edited_by` bigint(20) unsigned DEFAULT NULL,
              `moderated_by` bigint(20) unsigned DEFAULT NULL,
              `creation_date` datetime NOT NULL,
              `last_modified` datetime NOT NULL,
              `post_text` text NOT NULL,
              `post_html` text NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");


        $inserts = <<<INSERTS
            INSERT INTO `forum_posts` (`id`, `topic_id`, `topic_start`, `authored_by`, `edited_by`, `moderated_by`, `creation_date`, `last_modified`, `post_text`, `post_html`) VALUES
                (1, 1, 1, 14, NULL, 14, '2014-12-05 13:29:40', '2014-12-05 13:29:40', 'Спинка!\r\n\r\n ------------------ 13:31:08 ------------------ \r\n\r\nВот она [img]http://apikabu.ru/img_n/2012-08_5/cnf.jpg[/img]', 'Спинка!<br />\r\n<br />\r\n ------------------ 13:31:08 ------------------ <br />\r\n<br />\r\nВот она  <p>\r\n<a class="colorbox" href="http://apikabu.ru/img_n/2012-08_5/cnf.jpg"><img src="http://apikabu.ru/img_n/2012-08_5/cnf.jpg" /></a>\r\n\r\n<a class="colorbox" href="http://062012.imgbb.ru/8/4/c/84c3279bf6baeff86ead375cdece8a12.jpg"><img src="http://062012.imgbb.ru/8/4/c/84c3279bf6baeff86ead375cdece8a12.jpg" /></a>\r\n\r\n<a class="colorbox" href="http://cs10340.vk.me/u71959743/-14/x_1c0ee32e.jpg"><img src="http://cs10340.vk.me/u71959743/-14/x_1c0ee32e.jpg" /></a></p> '),
                (2, 1, 0, 16, NULL, 16, '2014-12-05 13:34:52', '2014-12-05 13:34:52', 'Кульные картинки', 'Кульные картинки'),
                (3, 2, 1, 16, NULL, 16, '2014-12-05 13:36:20', '2014-12-05 13:36:20', 'С++ сила', 'С++ сила'),
                (5, 2, 0, 14, NULL, 14, '2014-12-05 13:38:21', '2014-12-05 13:38:21', 'Жрать не просила', 'Жрать не просила'),
                (11, 6, 1, 11, NULL, 11, '2014-12-05 13:43:33', '2014-12-05 13:43:33', 'Как сделать?', 'Как сделать?'),
                (12, 6, 0, 14, NULL, 14, '2014-12-05 13:44:43', '2014-12-05 13:44:43', 'А гуглить пробовал?', 'А гуглить пробовал?'),
                (13, 7, 1, 14, NULL, 14, '2014-12-05 13:45:53', '2014-12-05 13:45:53', 'Шикарная ава!', 'Шикарная ава!'),
                (14, 7, 0, 11, NULL, 11, '2014-12-05 13:47:49', '2014-12-05 13:47:49', 'ЭТО ВАМ НЕ ЭТО', 'ЭТО ВАМ НЕ ЭТО'),
                (15, 8, 1, 11, NULL, 11, '2014-12-05 13:49:12', '2014-12-05 13:49:12', 'Не правильно работает счетчик сообщений в топе последних\r\nНужно сделать -1. Если автор только создал тему, то будет 0 сообщений, а сейчас 1 сообщение\r\n\r\n ------------------ 13:51:24 ------------------ \r\n\r\nну более привычно как на форуме. типа первое сообщение есть вопрос, а не ответ', 'Не правильно работает счетчик сообщений в топе последних<br />\r\nНужно сделать -1. Если автор только создал тему, то будет 0 сообщений, а сейчас 1 сообщение<br />\r\n<br />\r\n ------------------ 13:51:24 ------------------ <br />\r\n<br />\r\nну более привычно как на форуме. типа первое сообщение есть вопрос, а не ответ'),
                (16, 8, 0, 14, NULL, 14, '2014-12-05 13:50:38', '2014-12-05 13:50:38', 'Ну, в целом корректное замечание. А чем старт топика не есть сообщение?', 'Ну, в целом корректное замечание. А чем старт топика не есть сообщение?'),
                (17, 9, 1, 11, NULL, 11, '2014-12-05 13:52:00', '2014-12-05 13:52:00', 'Нужно сабмитить форму по контрол + энтер, а то не удобно клацать мышкой &quot;Отправить месагу&quot;\r\n\r\n ------------------ 13:53:46 ------------------ \r\n\r\nНихуя себе перделки. эТо юзабилити\r\n\r\n ------------------ 13:54:03 ------------------ \r\n\r\nэ, а че маты не отправляет?\r\n\r\n ------------------ 13:54:27 ------------------ \r\n\r\nглюк, щас, должно было добавить новый пост, а оно добавило к моему', 'Нужно сабмитить форму по контрол + энтер, а то не удобно клацать мышкой &quot;Отправить месагу&quot;<br />\r\n<br />\r\n ------------------ 13:53:46 ------------------ <br />\r\n<br />\r\nНихуя себе перделки. эТо юзабилити<br />\r\n<br />\r\n ------------------ 13:54:03 ------------------ <br />\r\n<br />\r\nэ, а че маты не отправляет?<br />\r\n<br />\r\n ------------------ 13:54:27 ------------------ <br />\r\n<br />\r\nглюк, щас, должно было добавить новый пост, а оно добавило к моему'),
                (18, 9, 0, 14, NULL, 14, '2014-12-05 13:52:58', '2014-12-05 13:52:58', 'Дельное замечание. Ну это уже перделки.', 'Дельное замечание. Ну это уже перделки.'),
                (19, 10, 1, 14, NULL, 14, '2014-12-05 13:55:20', '2014-12-05 13:55:20', 'Если вы искали тему &quot;[link=http://palmali.deep-host.ru/Форум/topic?id=8]Счетчик в [b][size=14]т[/size][/b]опе[/link]&quot; то попали не туда.\r\n\r\nЭта тема именно про счетчик в [b][size=14]п[/size][/b]опе!', 'Если вы искали тему &quot; <a target="_blank" href="http://palmali.deep-host.ru/Форум/topic?id=8">Счетчик в  <strong> <span style="line-height:14pt; font-size:14pt;">т</span> </strong> опе</a> &quot; то попали не туда.<br />\r\n<br />\r\nЭта тема именно про счетчик в  <strong> <span style="line-height:14pt; font-size:14pt;">п</span> </strong> опе!'),
                (20, 10, 0, 11, NULL, 11, '2014-12-05 13:56:04', '2014-12-05 13:56:04', 'Вспомнился анек про &quot;а ручки то вот они&quot;', 'Вспомнился анек про &quot;а ручки то вот они&quot;'),
                (21, 11, 1, 11, NULL, 11, '2014-12-05 13:57:33', '2014-12-05 13:57:49', 'Если в теме уже есть сообщение то должен создасться новый пост, а оно добавляет к существующему\r\n[link]http://palmali.deep-host.ru/%D0%A4%D0%BE%D1%80%D1%83%D0%BC/topic?id=9#post-17[/link]', 'Если в теме уже есть сообщение то должен создасться новый пост, а оно добавляет к существующему<br />\r\n <a target="_blank" href="http://palmali.deep-host.ru/%D0%A4%D0%BE%D1%80%D1%83%D0%BC/topic?id=9#post-17">http://palmali.deep-host.ru/%D0%A4%D0%BE%D1%80%D1%83%D0%BC/topic?id=9#post-17</a> '),
                (22, 11, 0, 14, NULL, 14, '2014-12-05 13:58:33', '2014-12-05 13:58:33', 'Да, на пхпму так же. Если подождешь (вроде) пять минут (не помню точное время в конфиге), то не добпавит в текущее, а создаст новое сообщение.\r\n\r\n ------------------ 13:59:22 ------------------ \r\n\r\nА, понял в чем касяк! Да, исправить надо.\r\n\r\n ------------------ 13:59:38 ------------------ \r\n\r\nИли вообще отрубить это гавно\r\n\r\n ------------------ 14:07:16 ------------------ \r\n\r\nЧорд, кажца тут вообще старая версия, не та которая на официальном сайте, надо будет гит пулл тут сделать вечерком. Я ж вроде исправлял этот касяк с разбивкой по времени.', 'Да, на пхпму так же. Если подождешь (вроде) пять минут (не помню точное время в конфиге), то не добпавит в текущее, а создаст новое сообщение.<br />\r\n<br />\r\n ------------------ 13:59:22 ------------------ <br />\r\n<br />\r\nА, понял в чем касяк! Да, исправить надо.<br />\r\n<br />\r\n ------------------ 13:59:38 ------------------ <br />\r\n<br />\r\nИли вообще отрубить это гавно<br />\r\n<br />\r\n ------------------ 14:07:16 ------------------ <br />\r\n<br />\r\nЧорд, кажца тут вообще старая версия, не та которая на официальном сайте, надо будет гит пулл тут сделать вечерком. Я ж вроде исправлял этот касяк с разбивкой по времени.'),
                (23, 11, 0, 16, NULL, 16, '2014-12-05 13:59:40', '2014-12-05 13:59:40', 'ну это ерунда яполная, так не юзабельно', 'ну это ерунда яполная, так не юзабельно'),
                (24, 6, 0, 16, NULL, 16, '2014-12-05 14:00:50', '2014-12-05 14:00:50', 'Конечно, чего ж я бы спрашивал!', 'Конечно, чего ж я бы спрашивал!'),
                (25, 6, 0, 14, NULL, 14, '2014-12-05 14:01:11', '2014-12-05 14:01:11', 'Ну потому что в гугле есть ответ!!!', 'Ну потому что в гугле есть ответ!!!'),
                (26, 6, 0, 11, NULL, 11, '2014-12-05 14:01:48', '2014-12-05 14:01:48', 'Бред', 'Бред'),
                (29, 12, 1, 14, NULL, 14, '2014-12-05 14:03:22', '2014-12-05 14:03:22', 'Может тут убрать все копирайты палмали и лишнее дело и нагнать весь пхпму для теста?\r\nВыложить сорцы на гитхаб и все такое..', 'Может тут убрать все копирайты палмали и лишнее гавно и нагнать весь пхпму для теста?<br />\r\nВыложить сорцы на гитхаб и все такое..'),
                (30, 12, 0, 16, NULL, 16, '2014-12-05 14:03:54', '2014-12-05 14:03:54', 'да, вместо остатков старого сайта запилить сюды документацию', 'да, вместо остатков старого сайт запилить сюды документацию'),
                (34, 13, 1, 17, NULL, 17, '2014-12-06 14:36:53', '2014-12-06 14:36:53', 'Куплю ИБП, входное питание AC 220V, выход DC 24V с долговременным током от 2А, обязательно поддержка использования стандартных батарей.\r\nРоссия, Санкт-Петербург.', 'Куплю ИБП, входное питание AC 220V, выход DC 24V с долговременным током от 2А, обязательно поддержка использования стандартных батарей.<br />\r\nРоссия, Санкт-Петербург.'),
                (35, 14, 1, 17, NULL, 17, '2014-12-06 14:39:33', '2014-12-06 14:53:23', 'bug check [link=javascript:alert( document.cookie )]check[/link]\r\n\r\n ------------------ 14:44:51 ------------------ \r\n\r\nБаг подтверждён.\r\nopera 12.16, после создания темы нет переадресации со страницы &quot;тема создана&quot;\r\nПредположительная причина - редирект средствами deprecated meta-тега на эту же самую страницу.', 'bug check  <a target="_blank" href="javascript:alert( document.cookie )">check</a> <br />\r\n<br />\r\n ------------------ 14:44:51 ------------------ <br />\r\n<br />\r\nБаг подтверждён.<br />\r\nopera 12.16, после создания темы нет переадресации со страницы &quot;тема создана&quot;<br />\r\nПредположительная причина - редирект средствами deprecated meta-тега на эту же самую страницу.'),
                (36, 14, 0, 17, NULL, 17, '2014-12-06 14:46:27', '2014-12-06 14:46:27', 'Добавление и редактирование сообщения - аналогично', 'Добавление и редактирование сообщения - аналогично'),
                (37, 14, 0, 14, NULL, 14, '2014-12-06 15:17:16', '2014-12-06 15:17:16', 'после создания темы нет переадресации со страницы &quot;тема создана&quot;\r\nПредположительная причина - редирект средствами deprecated meta-тега на эту же самую страницу. \r\n\r\nОго, как интересно. метатег депрекейтед, а заголовок метарефреш не поддерживается всеми браузерами. Браво!\r\n\r\n ------------------ 15:23:44 ------------------ \r\n\r\nА насчет document.cookie да, средства фильтрации имеются, но тут видимо не подключены.\r\nВобщем, надо выкинуть лишнее, привести в порядок и положить сорцы на гитхаб.', 'после создания темы нет переадресации со страницы &quot;тема создана&quot;<br />\r\nПредположительная причина - редирект средствами deprecated meta-тега на эту же самую страницу. <br />\r\n<br />\r\nОго, как интересно. метатег депрекейтед, а заголовок метарефреш не поддерживается всеми браузерами. Браво!<br />\r\n<br />\r\n ------------------ 15:23:44 ------------------ <br />\r\n<br />\r\nА насчет document.cookie да, средства фильтрации имеются, но тут видимо не подключены.<br />\r\nВобщем, надо выкинуть лишнее, привести в порядок и положить сорцы на гитхаб.'),
                (38, 14, 0, 14, NULL, 14, '2014-12-06 15:32:23', '2014-12-06 15:32:23', 'Ну или у меня есть личный гит-репозиторий на который ходят только по ключикам.', 'Ну или у меня есть личный гит-репозиторий на который ходят только по ключикам.'),
                (39, 14, 0, 17, NULL, 17, '2014-12-06 17:33:44', '2014-12-06 17:33:44', 'Сарказм неуместен.\r\nТы в известной степени консервативен (gnome2), я тоже (opera 12).\r\nДа, метарефреш deprecated. Нет, опера его отлично умеет, имеющийся же форум работает. А вот редирект на эту же самую страницу с якорем очевидно воспринимает как только переход по якорю. Не вижу причин, почему бы точно так же не вести себя другим браузерам.', 'Сарказм неуместен.<br />\r\nТы в известной степени консервативен (gnome2), я тоже (opera 12).<br />\r\nДа, метарефреш deprecated. Нет, опера его отлично умеет, имеющийся же форум работает. А вот редирект на эту же самую страницу с якорем очевидно воспринимает как только переход по якорю. Не вижу причин, почему бы точно так же не вести себя другим браузерам.'),
                (40, 14, 0, 14, NULL, 14, '2014-12-06 18:38:05', '2014-12-06 18:39:16', 'Сарказм не относился к тебе лично. Он был направлен в сторону тех кто задепрекейтил метатег, и тех разрабов клиентов, которые забили на новый хттп 1.1 заголовок метарефреша', 'Сарказм не относился к тебе лично. Он был направлен в сторону тех кто задепрекейтил метатег, и тех разрабов клиентов, которые забили на новый хттп 1.1 заголовок метарефреша');");
INSERTS;
        $this->addSql($inserts);

        $this->addSql("DROP TABLE IF EXISTS `forum_subforums`;");
        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `forum_subforums` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `sort` bigint(20) unsigned NOT NULL DEFAULT '0',
              `forum_id` bigint(20) unsigned NOT NULL,
              `title` varchar(255) NOT NULL,
              `description` text,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $inserts = <<<INSERTS
            INSERT INTO `forum_subforums` (`id`, `sort`, `forum_id`, `title`, `description`) VALUES
                (1, 1, 1, 'Программирование на PHP', 'Обсуждение различных вопросов программирования на PHP, включая общие вопросы, методики, ООП, работу с графикой, протоколами и др.'),
                (2, 2, 1, 'Вопросы новичков', 'Если Вы - начинающий, и не знаете, в какую категорию задать вопрос - Вам сюда.'),
                (3, 3, 1, 'Объектно-ориентированное программирование', 'ООП и все вопросы, принципы, парадигмы, задачи и их решения'),
                (4, 1, 2, 'JavaScript & VBScript', 'Все вопросы связаные с JavaScript & VBScript'),
                (5, 2, 2, 'HTML, Дизайн & CSS', 'Все вопросы по дизайнерской части'),
                (6, 3, 2, 'Программное обеспечение', 'ПО для разработчиков [Flash, Photoshop, PHPExpertEditor, FrontPage.. Обсуждение и вопросы]'),
                (7, 4, 1, 'Работа с сетью', 'Сокеты, curl, почта'),
                (8, 5, 1, 'HTTP и PHP', 'AJAX, сессии, uploads, авторизация');
INSERTS;
        $this->addSql($inserts);

        $this->addSql("DROP TABLE IF EXISTS `forum_subforums_stat`;");
        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `forum_subforums_stat` (
              `subforum_id` bigint(20) unsigned NOT NULL,
              `topics_count` bigint(20) unsigned NOT NULL,
              `posts_count` bigint(20) unsigned NOT NULL,
              `last_post_id` bigint(20) unsigned NOT NULL,
              UNIQUE KEY `subforum_id` (`subforum_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $inserts = <<<INSERTS
            INSERT INTO `forum_subforums_stat` (`subforum_id`, `topics_count`, `posts_count`, `last_post_id`) VALUES
                (1, 1, 2, 2),
                (2, 1, 2, 5),
                (3, 4, 9, 23),
                (4, 0, 0, 0),
                (5, 0, 0, 0),
                (6, 1, 1, 34),
                (7, 4, 15, 40),
                (8, 0, 0, 0);
INSERTS;
        $this->addSql($inserts);

        $this->addSql("DROP TABLE IF EXISTS `forum_topics`;");
        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `forum_topics` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `subforum_id` bigint(20) unsigned NOT NULL,
              `authored_by` bigint(20) unsigned NOT NULL,
              `title` varchar(255) NOT NULL,
              `description` varchar(255) DEFAULT NULL,
              `creation_date` datetime NOT NULL,
              `last_modified` datetime NOT NULL,
              `is_locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
              `is_important` tinyint(1) unsigned NOT NULL DEFAULT '0',
              `is_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`),
              KEY `is_closed` (`is_closed`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $inserts = <<<INSERTS
            INSERT INTO `forum_topics` (`id`, `subforum_id`, `authored_by`, `title`, `description`, `creation_date`, `last_modified`, `is_locked`, `is_important`, `is_closed`) VALUES
                (1, 1, 14, 'Тестовая тема', 'Это описание', '2014-12-05 13:29:40', '2014-12-05 13:34:52', 0, 0, 0),
                (2, 2, 16, 'Всё плохо', 'что же дальше делать?', '2014-12-05 13:36:20', '2014-12-05 13:38:21', 0, 0, 0),
                (6, 7, 11, 'Как послать Ajax запрос на другой домен', '', '2014-12-05 13:43:32', '2014-12-05 14:01:48', 0, 0, 0),
                (7, 7, 14, 'К пользователю stewie', 'Личный публичный вопрос', '2014-12-05 13:45:53', '2014-12-05 13:47:49', 0, 1, 0),
                (8, 3, 11, 'Счетчик в топе', '', '2014-12-05 13:49:12', '2014-12-05 13:51:24', 1, 0, 0),
                (9, 3, 11, 'Добавить ответ по контрол+ентер', '', '2014-12-05 13:52:00', '2014-12-05 13:54:27', 0, 0, 0),
                (10, 3, 14, 'Счетчик в попе', '', '2014-12-05 13:55:20', '2014-12-05 13:56:04', 0, 0, 0),
                (11, 3, 11, 'Сообщение не добавляется как новое', '', '2014-12-05 13:57:33', '2014-12-05 14:07:16', 1, 1, 0),
                (12, 7, 14, 'Можт сюда нагнать весь пхпсу?', '', '2014-12-05 14:03:22', '2014-12-05 14:03:54', 0, 0, 0),
                (13, 6, 17, 'UPS DC 24V', '', '2014-12-06 14:36:53', '2014-12-06 14:36:53', 0, 0, 0),
                (14, 7, 17, 'bug check', '', '2014-12-06 14:39:33', '2014-12-06 18:38:05', 0, 0, 0);
INSERTS;
        $this->addSql($inserts);

        $this->addSql("DROP TABLE IF EXISTS `forum_topics_stat`;");
        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `forum_topics_stat` (
              `topic_id` bigint(20) unsigned NOT NULL,
              `posts_count` bigint(20) unsigned NOT NULL,
              `views_count` bigint(20) unsigned NOT NULL DEFAULT '0',
              `last_post_id` bigint(20) unsigned NOT NULL,
              UNIQUE KEY `topic_id` (`topic_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $inserts = <<<INSERTS
            INSERT INTO `forum_topics_stat` (`topic_id`, `posts_count`, `views_count`, `last_post_id`) VALUES
                (1, 2, 51, 2),
                (2, 2, 48, 5),
                (6, 5, 358, 26),
                (7, 2, 51, 14),
                (8, 2, 56, 16),
                (9, 2, 308, 18),
                (10, 2, 53, 20),
                (11, 3, 57, 23),
                (12, 2, 65, 30),
                (13, 1, 53, 34),
                (14, 6, 117, 40);
INSERTS;
        $this->addSql($inserts);

        $this->addSql("DROP TABLE IF EXISTS `groups`;");
        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `groups` (
              `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
              `is_protected` tinyint(1) unsigned NOT NULL DEFAULT '0',
              `priority` smallint(4) unsigned NOT NULL,
              `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $inserts = <<<INSERTS
            INSERT INTO `groups` (`id`, `is_protected`, `priority`, `name`) VALUES
                (1, 1, 1001, 'Guest'),
                (2, 1, 1000, 'User'),
                (3, 1, 0, 'CLI (cron)'),
                (4, 1, 0, 'Root');
INSERTS;
        $this->addSql($inserts);

        $this->addSql("DROP TABLE IF EXISTS `groups_permissions`;");
        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `groups_permissions` (
              `group_id` smallint(5) unsigned NOT NULL,
              `permission_id` smallint(5) unsigned NOT NULL,
              PRIMARY KEY (`group_id`,`permission_id`),
              UNIQUE KEY `pk_revert` (`permission_id`,`group_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->addSql("DROP TABLE IF EXISTS `members`;");
        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `members` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `group_id` smallint(5) unsigned NOT NULL,
              `cookie` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
              `email` varchar(255) NOT NULL,
              `login` varchar(255) NOT NULL,
              `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
              `time_zone` char(6) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
              `creation_date` datetime NOT NULL,
              `last_ip` binary(16) NOT NULL COMMENT 'use php.net/inet_pton',
              `last_visit` datetime NOT NULL,
              `status` tinyint(1) unsigned NOT NULL,
              `activation_hash` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
              `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `email` (`email`),
              UNIQUE KEY `login` (`login`),
              KEY `activation_hash` (`activation_hash`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $inserts = <<<INSERTS
            INSERT INTO `members` (`id`, `group_id`, `cookie`, `email`, `login`, `password`, `time_zone`, `creation_date`, `last_ip`, `last_visit`, `status`, `activation_hash`, `avatar`) VALUES
                (2, 2, 'd5762eca435f5c89805498ff2f9f2204', 'admin@mail.ru', 'admin', '21232f297a57a5a743894a0e4a801fc3', '', '2014-03-09 07:43:03', '194.84.234.27\0\0\0', '2014-04-14 12:00:26', 0, '', NULL),
                (7, 2, 'dcec1c5c9c5616ff587927e96b6ec7df', 'test5@example.com', 'Буратино', 'c4ca4238a0b923820dcc509a6f75849b', '+04:00', '2014-03-29 12:34:38', '85.26.164.255\0\0\0', '2014-12-05 13:58:52', 0, '', 'bbca5484eab6f926c094adcec51af96b.jpg'),
                (9, 2, '92ec2e4bb6b311d90abd400134d52f0d', 'test7@example.com', 'jack', 'c4ca4238a0b923820dcc509a6f75849b', '+04:00', '2014-03-31 16:45:01', '192.168.95.100\0\0', '2014-12-05 14:15:06', 0, '', 'b9dddf1a55f7c608fe3408f1ae82110a.jpg'),
                (10, 2, '1072771cde958afcc7d07753b713f5d3', 'test8@example.com', 'mike', 'c4ca4238a0b923820dcc509a6f75849b', '', '2014-04-07 14:19:46', '0.0.0.0\0\0\0\0\0\0\0\0\0', '0000-00-00 00:00:00', 3, '', NULL),
                (11, 2, 'c9853c0a618ce542a5a9d24b7a327dd0', 'test9@example.com', 'stewie', 'c4ca4238a0b923820dcc509a6f75849b', '+04:00', '2014-04-07 14:21:55', '31.172.138.37\0\0\0', '2014-12-05 16:56:56', 0, '', '247a58b17fb6ab57dd5c999f6ada48d4.jpg'),
                (12, 2, '211e4e226e7f9eeb219e54aaad0f02a7', 'test10@example.com', 'dragon', 'c4ca4238a0b923820dcc509a6f75849b', '', '2014-04-07 14:23:16', '0.0.0.0\0\0\0\0\0\0\0\0\0', '0000-00-00 00:00:00', 0, '', NULL),
                (13, 2, 'a147864d0e9e95b5296a3c2554020e8b', 'serg@mail.ru', 'serg', 'e10adc3949ba59abbe56e057f20f883e', '', '2014-04-14 10:59:59', '194.84.234.27\0\0\0', '2014-04-14 14:38:36', 0, '', NULL),
                (14, 2, '1893b170e5085b53b4fd8d02b1071722', 'deepvarvar@mail.ru', 'deep', 'e10adc3949ba59abbe56e057f20f883e', '+04:00', '2014-12-05 13:24:59', '194.226.176.200\0', '2014-12-08 11:32:14', 0, '', '40aa405fd1bc20d61ae8364bf4da8f44.jpg'),
                (15, 2, '529bd57d2460022195767acbc1e7b467', 'bibi@example.com', 'Пистолет', 'e10adc3949ba59abbe56e057f20f883e', '', '2014-12-05 13:32:12', '0.0.0.0\0\0\0\0\0\0\0\0\0', '0000-00-00 00:00:00', 3, '', NULL),
                (16, 2, 'a4d3d3d8765e26438b4521fe309e7cd1', 'bibi_jones@mailinator.com', 'Матрос', 'e10adc3949ba59abbe56e057f20f883e', '+04:00', '2014-12-05 13:34:00', '31.172.138.37\0\0\0', '2014-12-05 14:26:34', 0, '', 'ae0bc38fe6c9f2d647988743570b7491.jpg'),
                (17, 2, '9c24fa8836422be0ea7bd98c1041c313', 'melkij@example.com', 'Мелкий', '62ad9a428daab3d8e0ab3fbac303378e', '', '2014-12-06 14:15:24', '188.134.75.4\0\0\0\0', '2014-12-07 14:18:31', 0, '', NULL);
INSERTS;
        $this->addSql($inserts);

        $this->addSql("DROP TABLE IF EXISTS `permissions`;");
        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `permissions` (
              `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $this->addSql("DROP TABLE IF EXISTS `session_data`;");
        $this->addSql(
            "CREATE TABLE IF NOT EXISTS `session_data` (
              `id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
              `data` blob NOT NULL,
              `updated_by` datetime NOT NULL,
              UNIQUE KEY `id` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE IF EXISTS `forum_forums`;');
        $this->addSql("DROP TABLE IF EXISTS `forum_forums_stat`;");
        $this->addSql("DROP TABLE IF EXISTS `forum_members`;");
        $this->addSql("DROP TABLE IF EXISTS `forum_posts`;");
        $this->addSql("DROP TABLE IF EXISTS `forum_subforums`;");
        $this->addSql("DROP TABLE IF EXISTS `forum_subforums_stat`;");
        $this->addSql("DROP TABLE IF EXISTS `forum_topics`;");
        $this->addSql("DROP TABLE IF EXISTS `forum_topics_stat`;");
        $this->addSql("DROP TABLE IF EXISTS `groups`;");
        $this->addSql("DROP TABLE IF EXISTS `groups_permissions`;");
        $this->addSql("DROP TABLE IF EXISTS `members`;");
        $this->addSql("DROP TABLE IF EXISTS `permissions`;");
        $this->addSql("DROP TABLE IF EXISTS `session_data`;");
    }
}
